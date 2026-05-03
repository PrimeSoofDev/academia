<?php
/**
 * generate_hash.php — Run once to get password hashes for seeding.
 * Usage: php generate_hash.php
 * DELETE this file before going to production.
 */

$passwords = ['password', 'admin123', 'student123', 'lecturer123'];

foreach ($passwords as $p) {
    $hash = password_hash($p, PASSWORD_BCRYPT, ['cost' => 12]);
    echo "$p => $hash\n";
}
