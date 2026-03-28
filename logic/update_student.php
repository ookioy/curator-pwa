<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    
    if ($id) {
        try {
            $pdo->beginTransaction();

            $sql = "UPDATE students 
                    SET full_name = ?, 
                        phone = ?, 
                        birth_date = ?,
                        home_address = ?, 
                        actual_address = ?,
                        education = ?,
                        languages = ?,
                        info_source = ?,
                        career_goal = ?,
                        programming_languages = ?,
                        activities = ?,
                        has_experience = ?
                    WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['full_name'],
                $_POST['phone'] ?: null,
                $_POST['birth_date'] ?: null,
                $_POST['home_address'] ?: null,
                $_POST['actual_address'] ?: null,
                $_POST['education'] ?: null,
                $_POST['languages'] ?: null,
                $_POST['info_source'] ?: null,
                $_POST['career_goal'] ?: null,
                $_POST['programming_languages'] ?: null,
                $_POST['activities'] ?: null,
                isset($_POST['has_experience']) ? 1 : 0,
                $id
            ]);

            if (isset($_POST['parents']) && is_array($_POST['parents'])) {
                foreach ($_POST['parents'] as $role => $pData) {
                    $pId    = $pData['id'] ?? '';
                    $pName  = trim($pData['full_name'] ?? '');
                    $pWork  = trim($pData['work_info'] ?? '');
                    $pPhone = trim($pData['phone'] ?? '');
                    $pType  = $pData['type'] ?? $role;

                    if (!empty($pId)) {
                        $sqlParent = "UPDATE parents SET full_name=?, work_info=?, phone=? WHERE id=? AND student_id=?";
                        $stmtParent = $pdo->prepare($sqlParent);
                        $stmtParent->execute([$pName, $pWork, $pPhone, $pId, $id]);
                    } elseif (!empty($pName)) {
                        $sqlParent = "INSERT INTO parents (student_id, full_name, type, work_info, phone) VALUES (?, ?, ?, ?, ?)";
                        $stmtParent = $pdo->prepare($sqlParent);
                        $stmtParent->execute([$id, $pName, $pType, $pWork, $pPhone]);
                    }
                }
            }

            $pdo->commit();

            header("Location: ../view_student.php?id=$id&updated=1");
            exit;
            
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            die("Помилка при оновленні: " . $e->getMessage());
        }
    }
}

header('Location: ../index.php');
exit;