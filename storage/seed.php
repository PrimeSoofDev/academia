<?php
/**
 * seed.php — Inserts a real demo user with a correct bcrypt hash.
 *
 * Run ONCE from the project root via CLI:
 *   php storage/seed.php
 *
 * Or visit: http://localhost/academia/storage/seed.php
 * (Delete this file after seeding in production!)
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/core/Database.php';

$cfg = require ROOT_PATH . '/config/database.php';
$dsn = "mysql:host={$cfg['host']};dbname={$cfg['database']};charset={$cfg['charset']}";
$pdo = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options']);

// Hash the demo password
$hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 12]);

// Update the VC user's password to a real hash
$stmt = $pdo->prepare("UPDATE users SET password = :h WHERE email = 'vc@demo.edu'");
$stmt->execute([':h' => $hash]);

echo "✅ Seed complete.\n";
echo "Login: vc@demo.edu | password: password | university code: demo-university\n";
