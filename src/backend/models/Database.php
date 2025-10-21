<?php
require_once '../config/config.php';

class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = 'utf8mb4';
    public $conn;
    
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false
    ];

    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $this->conn = new PDO($dsn, $this->username, $this->password, $this->options);
            
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            throw new Exception("Database connection failed");
        }
        
        return $this->conn;
    }

    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query execution error: " . $e->getMessage());
            throw new Exception("Database query failed");
        }
    }

    public function fetchOne($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetch();
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }

    public function insert($sql, $params = []) {
        $this->executeQuery($sql, $params);
        return $this->conn->lastInsertId();
    }

    public function execute($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount();
    }

    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollBack() {
        return $this->conn->rollBack();
    }

    public function tableExists($tableName) {
        $sql = "SHOW TABLES LIKE ?";
        $stmt = $this->executeQuery($sql, [$tableName]);
        return $stmt->rowCount() > 0;
    }

    public function getTableSchema($tableName) {
        $sql = "DESCRIBE {$tableName}";
        return $this->fetchAll($sql);
    }

    public function quote($value) {
        return $this->conn->quote($value);
    }

    public function checkConnection() {
        try {
            $this->getConnection();
            $this->executeQuery("SELECT 1");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getDatabaseInfo() {
        $info = [];

        $version = $this->fetchOne("SELECT VERSION() as version");
        $info['version'] = $version['version'];

        $size = $this->fetchOne("
            SELECT SUM(data_length + index_length) as size 
            FROM information_schema.TABLES 
            WHERE table_schema = ?", 
            [$this->db_name]
        );
        $info['database_size'] = $size['size'];

        $tables = $this->fetchOne("
            SELECT COUNT(*) as count 
            FROM information_schema.TABLES 
            WHERE table_schema = ?", 
            [$this->db_name]
        );
        $info['table_count'] = $tables['count'];
        
        return $info;
    }

    public function backupSchema($backupPath = null) {
        if (!$backupPath) {
            $backupPath = '../backups/schema_backup_' . date('Y-m-d_H-i-s') . '.sql';
        }

        $backupDir = dirname($backupPath);
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $tables = $this->fetchAll("SHOW TABLES");
        $backupContent = "";
        
        foreach ($tables as $table) {
            $tableName = $table['Tables_in_' . $this->db_name];

            $createTable = $this->fetchOne("SHOW CREATE TABLE `{$tableName}`");
            $backupContent .= $createTable['Create Table'] . ";\n\n";
        }
        
        file_put_contents($backupPath, $backupContent);
        return $backupPath;
    }

    public function cleanupOldRecords($table, $dateColumn, $daysToKeep = 30) {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysToKeep} days"));
        $sql = "DELETE FROM {$table} WHERE {$dateColumn} < ?";
        return $this->execute($sql, [$cutoffDate]);
    }

    public function bulkInsert($table, $columns, $data) {
        if (empty($data)) {
            return 0;
        }
        
        $placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
        $values = implode(',', array_fill(0, count($data), $placeholders));
        
        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ") VALUES {$values}";

        $flatData = [];
        foreach ($data as $row) {
            foreach ($row as $value) {
                $flatData[] = $value;
            }
        }
        
        $stmt = $this->executeQuery($sql, $flatData);
        return $stmt->rowCount();
    }

    public function __destruct() {
        $this->conn = null;
    }
}

class DatabaseMigration {
    private $db;
    private $migrationsTable = 'database_migrations';
    
    public function __construct(Database $database) {
        $this->db = $database;
    }

    public function createMigrationsTable() {
        $sql = "
            CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->executeQuery($sql);
    }

    public function runMigration($migrationFile) {
        $migrationName = basename($migrationFile, '.php');

        $existing = $this->db->fetchOne(
            "SELECT id FROM {$this->migrationsTable} WHERE migration = ?", 
            [$migrationName]
        );
        
        if ($existing) {
            return false;
        }

        require_once $migrationFile;

        $className = preg_replace('/[0-9]+_/', '', $migrationName);
        $className = str_replace('_', '', ucwords($className, '_'));
        
        if (class_exists($className)) {
            $migration = new $className($this->db);
            $migration->up();

            $batch = $this->getNextBatchNumber();
            $this->db->executeQuery(
                "INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)",
                [$migrationName, $batch]
            );
            
            return true;
        }
        
        return false;
    }

    public function rollbackMigration($batch = null) {
        if ($batch === null) {
            $batch = $this->getLastBatchNumber();
        }
        
        $migrations = $this->db->fetchAll(
            "SELECT migration FROM {$this->migrationsTable} WHERE batch = ? ORDER BY id DESC",
            [$batch]
        );
        
        foreach ($migrations as $migration) {
            $migrationFile = '../migrations/' . $migration['migration'] . '.php';
            
            if (file_exists($migrationFile)) {
                require_once $migrationFile;
                
                $className = preg_replace('/[0-9]+_/', '', $migration['migration']);
                $className = str_replace('_', '', ucwords($className, '_'));
                
                if (class_exists($className)) {
                    $migrationInstance = new $className($this->db);
                    $migrationInstance->down();
                }
                
                $this->db->executeQuery(
                    "DELETE FROM {$this->migrationsTable} WHERE migration = ?",
                    [$migration['migration']]
                );
            }
        }
        
        return count($migrations);
    }

    private function getNextBatchNumber() {
        $lastBatch = $this->db->fetchOne(
            "SELECT MAX(batch) as max_batch FROM {$this->migrationsTable}"
        );
        
        return ($lastBatch['max_batch'] ?? 0) + 1;
    }

    private function getLastBatchNumber() {
        $lastBatch = $this->db->fetchOne(
            "SELECT MAX(batch) as max_batch FROM {$this->migrationsTable}"
        );
        
        return $lastBatch['max_batch'] ?? 0;
    }
}
