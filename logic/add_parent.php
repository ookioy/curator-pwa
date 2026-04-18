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
    $pdo->prepare('
        INSERT INTO parents (student_id, full_name, type, work_info, phone)
        VALUES (?, ?, ?, ?, ?)
    ')->execute([
        $studentId,
        $_POST['full_name'],
        $_POST['type']      ?? 'mother',
        $_POST['work_info'] ?: null,
        (isset($_POST['phone_prefix_new'], $_POST['phone_number_new']) && trim($_POST['phone_number_new']) !== ''
            ? $_POST['phone_prefix_new'] . trim($_POST['phone_number_new'])
            : ($_POST['phone'] ?: null)),
    ]);

    redirect("../edit_parents.php?student_id=$studentId&updated=1");
} catch (Exception $e) {
    die('Помилка при додаванні батька: ' . $e->getMessage());
}
