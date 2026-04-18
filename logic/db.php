<?php
$dsn = 'mysql:host=mysql-curator.alwaysdata.net;dbname=curator_db;charset=utf8mb4';
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, 'curator', 'w1m5YB65', $options);
} catch (\PDOException $e) {
    die('Помилка підключення: ' . $e->getMessage());
}
