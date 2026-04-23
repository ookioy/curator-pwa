<?php
require 'logic/db.php';
require 'logic/auth.php';
require 'logic/helpers.php';

protectPage($pdo);

$students  = $pdo->query('SELECT id, full_name, phone FROM students ORDER BY full_name ASC')->fetchAll();
$pageTitle = 'Головна - Список групи';
$pageCss   = 'list.css';
require 'blocks/header.php';
?>

<main>
    <h2>Список групи</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <p class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>Студента успішно видалено!</strong>
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <p class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>Студента успішно додано!</strong>
        </p>
    <?php endif; ?>

    <?php if (empty($students)): ?>
        <p><em>Студентів ще не додано.</em></p>
    <?php else: ?>
        <p class="student-count">Всього студентів: <strong><?= count($students) ?></strong></p>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ПІБ Студента</th>
                    <th>Телефон</th>
                    <th class="center" style="width:150px">Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><strong><?= e($s['full_name']) ?></strong></td>
                    <td>
                        <?= e($s['phone'] ?? '—') ?>
                        <?php if (!empty($s['phone'])): ?>
                            <a href="tel:<?= preg_replace('/[^\d+]/', '', $s['phone']) ?>"><i class="fa-solid fa-phone"></i></a>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <a href="view_student.php?id=<?= $s['id'] ?>" class="action-btn btn-view" title="Переглянути деталі">
                            <i class="fa-solid fa-eye fa-lg"></i>
                        </a>
                        <a href="edit_student.php?id=<?= $s['id'] ?>" class="action-btn btn-edit" title="Редагувати">
                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                        </a>
                        <form action="logic/delete_student.php" method="POST" class="inline-form">
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