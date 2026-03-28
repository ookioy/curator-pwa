<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    
    if ($student_id) {
        try {
            $pdo->beginTransaction();

            $stmt_parents = $pdo->prepare("DELETE FROM parents WHERE student_id = ?");
            $stmt_parents->execute([$student_id]);

            $stmt_student = $pdo->prepare("DELETE FROM students WHERE id = ?");
            $stmt_student->execute([$student_id]);

            $pdo->commit();

            header('Location: ../index.php?deleted=1');
            exit;
            
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            die("Помилка при видаленні студента: " . $e->getMessage());
        }
    }
}

header('Location: ../index.php');
exit;