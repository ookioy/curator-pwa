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
    $pdo->prepare('DELETE FROM parents WHERE id = ?')->execute([$parentId]);
    redirect("../edit_parents.php?student_id=$studentId&updated=1");

} catch (Exception $e) {
    die('Помилка при видаленні батька: ' . $e->getMessage());
}
