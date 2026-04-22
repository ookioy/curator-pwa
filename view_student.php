<?php
require 'logic/db.php';
require 'logic/auth.php';
require 'logic/helpers.php';

protectPage($pdo);

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

$stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) {
    die('Студента не знайдено!');
}

$stmtP = $pdo->prepare('SELECT * FROM parents WHERE student_id = ?');
$stmtP->execute([$id]);
$parents = $stmtP->fetchAll();

$pageTitle = 'Перегляд: ' . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">

        <a href="index.php">&larr; Назад до списку</a>


        <a href="edit_student.php?id=<?= $student['id'] ?>">
            <button type="button">
                <i class="fa-solid fa-pen"></i> Редагувати профіль
            </button>
        </a>
    </div>

    <h2>Особова картка студента</h2>

    <?php if (isset($_GET['updated'])): ?>
        <p style="color: green; background: #eaffea; padding: 10px; border: 1px solid green;">
            Дані успішно оновлено!
        </p>
    <?php endif; ?>

    <fieldset>
        <legend><strong>Основна інформація</strong></legend>
        <table border="0" cellpadding="8" cellspacing="0" width="100%">
            <?php
            $fields = [
                'ПІБ'                  => $student['full_name'],
                'Дата народження'      => $student['birth_date']          ?? '—',
                'Адреса реєстрації'    => $student['home_address']         ?? '—',
                'Фактична адреса'      => $student['actual_address']       ?? '—',
                'Освіта'               => $student['education']            ?? '—',
                'Мови'                 => $student['languages']            ?? '—',
                'Джерело інформації'   => $student['info_source']          ?? '—',
                'Кар\'єрна ціль'       => $student['career_goal']          ?? '—',
                'Мови програмування'   => $student['programming_languages'] ?? '—',
                'Хобі/Інтереси'        => $student['activities']           ?? '—',
            ];
            foreach ($fields as $label => $value): ?>
                <tr>
                    <td width="30%"><strong><?= e($label) ?>:</strong></td>
                    <td><?= nl2br(e($value)) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Телефон:</strong></td>
                <td>
                    <?= e($student['phone'] ?? '—') ?>
                    <?php if (!empty($student['phone'])): ?>
                        <a href="tel:<?= preg_replace('/[^\d+]/', '', $student['phone']) ?>"><i class="fa-solid fa-phone"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Досвід роботи:</strong></td>
                <td><?= $student['has_experience'] ? '✅ Є досвід' : '❌ Немає досвіду' ?></td>
            </tr>
        </table>
    </fieldset>

    <fieldset>
        <legend><strong>Батьки / Опікуни</strong></legend>
        <?php if (empty($parents)): ?>
            <p><em>Інформація про батьків відсутня.</em></p>
        <?php else: ?>
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th>Роль</th>
                        <th>ПІБ</th>
                        <th>Робота</th>
                        <th>Телефон</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $roles = ['father' => 'Батько', 'mother' => 'Мати'];
                    foreach ($parents as $p):
                    ?>
                        <tr>
                            <td><?= e($roles[$p['type']] ?? 'Опікун') ?></td>
                            <td><?= e($p['full_name']) ?></td>
                            <td><?= e($p['work_info'] ?? '—') ?></td>
                            <td>
                                <?= e($p['phone'] ?? '—') ?>
                                <?php if (!empty($p['phone'])): ?>
                                    <a href="tel:<?= preg_replace('/[^\d+]/', '', $p['phone']) ?>"><i class="fa-solid fa-phone"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </fieldset>

    <div class="form-actions">
        <button type="button" onclick="window.location.href='index.php'">Назад до списку</button>
    </div>
</main>

<?php require 'blocks/footer.php'; ?>