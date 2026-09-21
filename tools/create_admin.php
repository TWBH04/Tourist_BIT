<?php
// Usage (CLI): php tools/create_admin.php <username> <password>
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
if ($argc < 3) { exit("Usage: php tools/create_admin.php <username> <password>\n"); }
$c = require __DIR__ . '/../config/config.php';
$pdo = new PDO("mysql:host={$c['db_host']};dbname={$c['db_name']};charset=utf8mb4", $c['db_user'], $c['db_pass'],
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$st = $pdo->prepare('INSERT INTO admin (username, password_hash) VALUES (?, ?)');
$st->execute([$argv[1], password_hash($argv[2], PASSWORD_DEFAULT)]);
echo "Admin '{$argv[1]}' created.\n";
