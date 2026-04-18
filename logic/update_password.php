<?php
require 'db.php';
require 'auth.php';
require 'helpers.php';

protectPage($pdo);
requirePost();

$current = $_POST['current_password'] ?? '';
$new     = $_POST['new_password']     ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$config = $pdo->prepare('SELECT setting_value FROM config WHERE setting_name = ?');
$config->execute(['admin_password']);
$row = $config->fetch();

if (!$row)                                         { redirect('../change_password.php?error=db');      }
if (!password_verify($current, $row['setting_value'])) { redirect('../change_password.php?error=current'); }
if (strlen($new) < 6)                              { redirect('../change_password.php?error=length');  }
if ($new !== $confirm)                             { redirect('../change_password.php?error=match');   }
if (password_verify($new, $row['setting_value']))  { redirect('../change_password.php?error=same');    }

try {
    $pdo->beginTransaction();
    $pdo->prepare('UPDATE config SET setting_value = ? WHERE setting_name = ?')
        ->execute([password_hash($new, PASSWORD_DEFAULT), 'admin_password']);
    $pdo->exec('DELETE FROM auth_tokens');
    $pdo->commit();

    setcookie('auth_token', '', time() - 3600, '/');
    session_destroy();
    redirect('../login.php?password_changed=1');

} catch (Exception $e) {
    $pdo->inTransaction() && $pdo->rollBack();
    redirect('../change_password.php?error=db');
}
