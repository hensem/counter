<?php
require_once 'db.php';

// Expire old logs: delete visitor_logs older than 1 day
global $pdo;
$pdo->exec("DELETE FROM visitor_logs WHERE visited_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");

echo "Old logs expired.\n";
?>
