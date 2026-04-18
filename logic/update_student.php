<?php
require 'db.php';
require 'auth.php';
require 'helpers.php';

protectPage($pdo);
requirePost();

$id = $_POST['id'] ?? null;
if (!$id) {
    redirect('../index.php');
}

try {
    $pdo->beginTransaction();

    $pdo->prepare('
        UPDATE students
        SET full_name = ?, phone = ?, birth_date = ?, home_address = ?,
            actual_address = ?, education = ?, languages = ?, info_source = ?,
            career_goal = ?, programming_languages = ?, activities = ?, has_experience = ?
        WHERE id = ?
    ')->execute([
        $_POST['full_name'],
        $_POST['phone']                 ?: null,
        $_POST['birth_date']            ?: null,
        $_POST['home_address']          ?: null,
        $_POST['actual_address']        ?: null,
        $_POST['education']             ?: null,
        $_POST['languages']             ?: null,
        $_POST['info_source']           ?: null,
        $_POST['career_goal']           ?: null,
        $_POST['programming_languages'] ?: null,
        $_POST['activities']            ?: null,
        isset($_POST['has_experience']) ? 1 : 0,
        $id,
    ]);

    foreach ($_POST['parents'] ?? [] as $role => $p) {
        $pId   = $p['id']        ?? '';
        $pName = trim($p['full_name'] ?? '');

        if ($pId !== '') {
            $pdo->prepare('
                UPDATE parents SET full_name = ?, work_info = ?, phone = ?
                WHERE id = ? AND student_id = ?
            ')->execute([
                $pName,
                trim($p['work_info'] ?? '') ?: null,
                trim($p['phone']     ?? '') ?: null,
                $pId,
                $id,
            ]);
        } elseif ($pName !== '') {
            $pdo->prepare('
                INSERT INTO parents (student_id, full_name, type, work_info, phone)
                VALUES (?, ?, ?, ?, ?)
            ')->execute([
                $id, $pName, $p['type'] ?? $role,
                trim($p['work_info'] ?? '') ?: null,
                trim($p['phone']     ?? '') ?: null,
            ]);
        }
    }

    $pdo->commit();
    redirect("../view_student.php?id=$id&updated=1");

} catch (Exception $e) {
    $pdo->inTransaction() && $pdo->rollBack();
    die('Помилка при оновленні: ' . $e->getMessage());
}
