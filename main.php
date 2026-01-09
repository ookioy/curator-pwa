<?php
session_start();
require 'db.php';

function checkAuth($pdo)
{
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) return true;

    if (isset($_COOKIE['auth_token'])) {
        $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
        $stmt->execute();
        $config = $stmt->fetch();

        $expected_token = md5($config['setting_value'] . 'salt123');

        if ($_COOKIE['auth_token'] === $expected_token) {
            $_SESSION['logged_in'] = true;
            return true;
        }
    }
    return false;
}

if (!checkAuth($pdo)) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <title>Головна сторінка</title>
</head>

<body>
    <h1>Вітаємо в системі!</h1>
    <a href="logout.php">Вийти (logout)</a>
</body>

</html>