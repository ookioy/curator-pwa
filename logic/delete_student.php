<?php
require 'db.php';
require 'auth.php';
require 'helpers.php';

protectPage($pdo);
requirePost();

$studentId = $_POST['student_id'] ?? null;
if (!$studentId) {
    redirect('../index.php');
}

try {
    $pdo->beginTransaction();
    $pdo->prepare('DELETE FROM parents  WHERE student_id = ?')->execute([$studentId]);
    $pdo->prepare('DELETE FROM students WHERE id = ?')->execute([$studentId]);
    $pdo->commit();
    redirect('../index.php?deleted=1');

} catch (Exception $e) {
    $pdo->inTransaction() && $pdo->rollBack();
    die('Помилка при видаленні студента: ' . $e->getMessage());
}
