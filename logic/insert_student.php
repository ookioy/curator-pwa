<?php
require 'db.php';
require 'auth.php';
require 'helpers.php';

protectPage($pdo);
requirePost();

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('
        INSERT INTO students
            (full_name, phone, birth_date, home_address, actual_address,
             education, languages, info_source, career_goal,
             programming_languages, activities, has_experience)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');

    $stmt->execute([
        $_POST['full_name'],
        $_POST['phone']               ?: null,
        $_POST['birth_date']          ?: null,
        $_POST['home_address']        ?: null,
        $_POST['actual_address']      ?: null,
        $_POST['education']           ?: null,
        $_POST['languages']           ?: null,
        $_POST['info_source']         ?: null,
        $_POST['career_goal']         ?: null,
        $_POST['programming_languages'] ?: null,
        $_POST['activities']          ?: null,
        isset($_POST['has_experience']) ? 1 : 0,
    ]);

    $studentId = $pdo->lastInsertId();

    if (!empty($_POST['p_full_name'])) {
        $stmtP = $pdo->prepare('
            INSERT INTO parents (student_id, full_name, type, work_info, phone)
            VALUES (?, ?, ?, ?, ?)
        ');
        foreach ($_POST['p_full_name'] as $i => $name) {
            if (trim($name) === '') {
                continue;
            }
            $stmtP->execute([
                $studentId,
                $name,
                $_POST['p_type'][$i]     ?? 'mother',
                $_POST['p_work_info'][$i] ?? null,
                $_POST['p_phone'][$i]    ?? null,
            ]);
        }
    }

    $pdo->commit();
    redirect('../index.php?success=1');

} catch (Exception $e) {
    $pdo->inTransaction() && $pdo->rollBack();
    die('Помилка при збереженні: ' . $e->getMessage());
}
