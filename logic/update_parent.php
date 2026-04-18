<?php
require 'db.php';
require 'auth.php';
require 'helpers.php';

protectPage($pdo);
requirePost();

$parentId  = $_POST['parent_id']  ?? null;
$studentId = $_POST['student_id'] ?? null;

if (!$parentId || !$studentId) {
    redirect('../index.php');
}

try {
    $pdo->prepare('
        UPDATE parents SET full_name = ?, type = ?, work_info = ?, phone = ?
        WHERE id = ?
    ')->execute([
        $_POST['full_name'],
        $_POST['type']      ?? 'mother',
        $_POST['work_info'] ?: null,
        $_POST['phone']     ?: null,
        $parentId,
    ]);

    redirect("../edit_parents.php?student_id=$studentId&updated=1");

} catch (Exception $e) {
    die('Помилка при оновленні батька: ' . $e->getMessage());
}
