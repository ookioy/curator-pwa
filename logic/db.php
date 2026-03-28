<?php
$host = 'mysql-currator-pwa.alwaysdata.net';
$db   = 'currator-pwa_curator_system';
$user = 'currator-pwa_curator_admin';
$pass = 'w1m5YB65';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Помилка підключення: " . $e->getMessage());
}