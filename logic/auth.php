<?php
session_start();

function checkAuth($pdo) {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) return true;

    if (isset($_COOKIE['auth_token'])) {
        $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
        $stmt->execute();
        $config = $stmt->fetch();

        if ($config) {
            $expected_token = md5($config['setting_value'] . 'salt123');
            if ($_COOKIE['auth_token'] === $expected_token) {
                $_SESSION['logged_in'] = true;
                return true;
            }
        }
    }
    return false;
}

function protectPage($pdo) {
    if (!checkAuth($pdo)) {
        header('Location: login.php');
        exit;
    }
}