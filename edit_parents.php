<?php
require 'logic/db.php';
require 'logic/auth.php';
require 'logic/helpers.php';

protectPage($pdo);

$studentId = $_GET['student_id'] ?? null;
if (!$studentId) { redirect('index.php'); }

$stmtS = $pdo->prepare('SELECT full_name FROM students WHERE id = ?');
$stmtS->execute([$studentId]);
$student = $stmtS->fetch();
if (!$student) { die('Студента не знайдено!'); }

$stmtP = $pdo->prepare('SELECT * FROM parents WHERE student_id = ? ORDER BY type');
$stmtP->execute([$studentId]);
$parents = $stmtP->fetchAll();

$pageTitle = 'Редагування батьків: ' . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <p><a href="view_student.php?id=<?= $studentId ?>">&larr; Назад до профілю</a></p>
    <h2>Редагування батьків/опікунів</h2>

    <?php if (isset($_GET['updated'])): ?>
        <p><strong>Зміни збережено!</strong></p>
    <?php endif; ?>

    <fieldset>
        <legend><strong>Поточні батьки/опікуни</strong></legend>

        <?php if (empty($parents)): ?>
            <p><em>Батьків ще не додано.</em></p>
        <?php else: ?>
            <?php foreach ($parents as $i => $p): ?>
            <fieldset>
                <legend>Батько/Мати/Опікун #<?= $i + 1 ?></legend>

                <form action="logic/update_parent.php" method="POST">
                    <input type="hidden" name="parent_id"  value="<?= $p['id'] ?>">
                    <input type="hidden" name="student_id" value="<?= $studentId ?>">
                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                        <tr>
                            <td width="25%"><label>ПІБ:</label></td>
                            <td><input type="text" name="full_name" value="<?= e($p['full_name']) ?>" size="50" required></td>
                        </tr>
                        <tr>
                            <td><label>Тип:</label></td>
                            <td>
                                <select name="type">
                                    <option value="mother" <?= $p['type'] === 'mother' ? 'selected' : '' ?>>Мати</option>
                                    <option value="father" <?= $p['type'] === 'father' ? 'selected' : '' ?>>Батько</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Місце роботи:</label></td>
                            <td><input type="text" name="work_info" value="<?= e($p['work_info'] ?? '') ?>" size="50"></td>
                        </tr>
                        <tr>
                            <td><label>Телефон:</label></td>
                            <td><input type="tel" name="phone" value="<?= e($p['phone'] ?? '') ?>" size="30"></td>
                        </tr>
                    </table>
                    <p><button type="submit">Оновити</button></p>
                </form>

                <form action="logic/delete_parent.php" method="POST">
                    <input type="hidden" name="parent_id"  value="<?= $p['id'] ?>">
                    <input type="hidden" name="student_id" value="<?= $studentId ?>">
                    <button type="submit">Видалити</button>
                </form>
            </fieldset>
            <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <legend><strong>Додати нового батька/опікуна</strong></legend>
        <form action="logic/add_parent.php" method="POST">
            <input type="hidden" name="student_id" value="<?= $studentId ?>">
            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td width="25%"><label for="new_full_name">ПІБ:</label></td>
                    <td><input type="text" id="new_full_name" name="full_name" size="50" required></td>
                </tr>
                <tr>
                    <td><label for="new_type">Тип:</label></td>
                    <td>
                        <select id="new_type" name="type">
                            <option value="mother">Мати</option>
                            <option value="father">Батько</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="new_work_info">Місце роботи:</label></td>
                    <td><input type="text" id="new_work_info" name="work_info" size="50"></td>
                </tr>
                <tr>
                    <td><label for="new_phone">Телефон:</label></td>
                    <td><input type="tel" id="new_phone" name="phone" size="30"></td>
                </tr>
            </table>
            <p><button type="submit"><strong>Додати</strong></button></p>
        </form>
    </fieldset>
</main>

<?php require 'blocks/footer.php'; ?>
