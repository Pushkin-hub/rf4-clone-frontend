<?php
$dbPath = 'rf4game.db'; // Путь к файлу базы данных SQLite

function dbConnect($path) {
  try {
    return new PDO('sqlite:' . $path);
  } catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

$db = dbConnect($dbPath); // Установите глобальное подключение к базе данных
?>
