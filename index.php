<?php
require 'logic/db.php';
require 'logic/auth.php';
require 'logic/helpers.php';

protectPage($pdo);

$students  = $pdo->query('SELECT id, full_name, phone FROM students ORDER BY full_name ASC')->fetchAll();
$pageTitle = 'Головна - Список групи';
require 'blocks/header.php';
?>

<main>
    <h2>Список групи</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <p style="color: green; background: #bfd0b9; padding: 10px;">
            <strong>Студента успішно видалено!</strong>
        </p>
    <?php endif; ?>

    <?php if (empty($students)): ?>
        <p><em>Студентів ще не додано.</em></p>
    <?php else: ?>
        <p>Всього студентів: <strong><?= count($students) ?></strong></p>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="left">ПІБ Студента</th>
                    <th align="left">Телефон</th>
                    <th align="center" width="150">Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><strong><?= e($s['full_name']) ?></strong></td>
                    <td>
                        <?= e($s['phone'] ?? '—') ?>
                        <?php if (!empty($s['phone'])): ?>
                            <a href="tel:<?= preg_replace('/[^\d+]/', '', $s['phone']) ?>">📞</a>
                        <?php endif; ?>
                    </td>
                    <td align="center">
                        <a href="view_student.php?id=<?= $s['id'] ?>" class="action-btn btn-view" title="Переглянути деталі">
                            <i class="fa-solid fa-eye fa-lg"></i>
                        </a>
                        <a href="edit_student.php?id=<?= $s['id'] ?>" class="action-btn btn-edit" title="Редагувати">
                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                        </a>
                        <form action="logic/delete_student.php" method="POST" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?= $s['id'] ?>">
                            <button type="submit" class="action-btn btn-delete" title="Видалити">
                                <i class="fa-solid fa-trash fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require 'blocks/footer.php'; ?>
