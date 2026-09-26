<?php

try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/../../config/counter.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create tables if not exist
$pdo->exec("
CREATE TABLE IF NOT EXISTS page_views (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  page TEXT,
  unique_count INTEGER DEFAULT 1,
  created_at TEXT
);
");

$pdo->exec("
CREATE TABLE IF NOT EXISTS visitor_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  page TEXT,
  visitor_hash BLOB,
  visited_at TEXT,
  UNIQUE(page, visitor_hash)
);
");
?>
