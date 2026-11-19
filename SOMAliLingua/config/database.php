<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

$host = '127.0.0.1';
$dbname = 'somalingua';
$dbuser = 'root';
$dbpass = ''; // Default XAMPP MySQL password is usually blank.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Start Apache and MySQL in XAMPP, then import database/somalingua.sql using phpMyAdmin. Check config/database.php if your MySQL credentials are different.');
}
