<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
    $stmt->execute();
    $config = $stmt->fetch();
    
    if (!$config) {
        header('Location: ../change_password.php?error=db');
        exit;
    }
    
    if (!password_verify($current_password, $config['setting_value'])) {
        header('Location: ../change_password.php?error=current');
        exit;
    }
    
    if (strlen($new_password) < 6) {
        header('Location: ../change_password.php?error=length');
        exit;
    }
    
    if ($new_password !== $confirm_password) {
        header('Location: ../change_password.php?error=match');
        exit;
    }
    
    if (password_verify($new_password, $config['setting_value'])) {
        header('Location: ../change_password.php?error=same');
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt_update = $pdo->prepare("UPDATE config SET setting_value = ? WHERE setting_name = 'admin_password'");
        $stmt_update->execute([$new_password_hash]);
        
        $pdo->exec("DELETE FROM auth_tokens");
        
        $pdo->commit();
        
        if (isset($_COOKIE['auth_token'])) {
            setcookie('auth_token', '', time() - 3600, "/");
        }
        
        session_destroy();
        
        header('Location: ../login.php?password_changed=1');
        exit;
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header('Location: ../change_password.php?error=db');
        exit;
    }
}

header('Location: ../change_password.php');
exit;