<?php
$dbPath = 'rf4game.db';

function dbConnect($path) {
  try {
    return new PDO('sqlite:' . $path);
  } catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

$db = dbConnect($dbPath);
?>
