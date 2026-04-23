<?php
require 'logic/db.php';
require 'logic/auth.php';
require 'logic/helpers.php';

protectPage($pdo);

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

$student = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$student->execute([$id]);
$student = $student->fetch();
if (!$student) {
    die('Студента не знайдено!');
}

$parentsList = $pdo->prepare('SELECT * FROM parents WHERE student_id = ?');
$parentsList->execute([$id]);

$parents = [
    'father' => ['id' => '', 'full_name' => '', 'work_info' => '', 'phone' => ''],
    'mother' => ['id' => '', 'full_name' => '', 'work_info' => '', 'phone' => '']
];
foreach ($parentsList->fetchAll() as $p) {
    if (isset($parents[$p['type']])) {
        $parents[$p['type']] = $p;
    }
}

[$savedPrefix, $savedNumber] = phonePrefix($student['phone'] ?? '');

$pageTitle = 'Редагування: ' . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <a href="index.php" class="back-link">&larr; Назад до списку</a>
    <h2>Редагування картки студента</h2>

    <?php if (isset($_GET['updated'])): ?>
        <p class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>Дані успішно оновлено!</strong>
        </p>
    <?php endif; ?>

    <form action="logic/update_student.php" method="POST">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">

        <fieldset>
            <legend><strong>Дані студента</strong></legend>
            <table class="form-table">
                <tr>
                    <td><label for="full_name">ПІБ Студента: <em>*</em></label></td>
                    <td><input type="text" id="full_name" name="full_name" value="<?= e($student['full_name']) ?>" size="40" required></td>
                </tr>
                <tr>
                    <td><label for="phone_number">Телефон:</label></td>
                    <td>
                        <div class="phone-field-wrap">
                            <select name="phone_prefix" id="phone_prefix" class="phone-prefix-select">
                                <?= phonePrefixOptions($savedPrefix) ?>
                            </select>
                            <input type="tel" id="phone_number" name="phone_number" class="phone-number-input"
                                placeholder="50 123 45 67" value="<?= e($savedNumber) ?>">
                        </div>
                        <input type="hidden" name="phone" id="phone_combined" value="<?= e($student['phone'] ?? '') ?>">
                    </td>
                </tr>
                <tr>
                    <td><label for="birth_date">Дата народження:</label></td>
                    <td><input type="date" id="birth_date" name="birth_date" value="<?= e($student['birth_date'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td><label for="home_address">Адреса реєстрації:</label></td>
                    <td><input type="text" id="home_address" name="home_address" value="<?= e($student['home_address'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="actual_address">Фактична адреса:</label></td>
                    <td><input type="text" id="actual_address" name="actual_address" value="<?= e($student['actual_address'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="education">Освіта:</label></td>
                    <td><input type="text" id="education" name="education" value="<?= e($student['education'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="languages">Мови:</label></td>
                    <td><input type="text" id="languages" name="languages" value="<?= e($student['languages'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="info_source">Джерело інформації:</label></td>
                    <td><input type="text" id="info_source" name="info_source" value="<?= e($student['info_source'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="career_goal">Кар'єрна ціль:</label></td>
                    <td><input type="text" id="career_goal" name="career_goal" value="<?= e($student['career_goal'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="programming_languages">Мови програмування:</label></td>
                    <td><input type="text" id="programming_languages" name="programming_languages" value="<?= e($student['programming_languages'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td valign="top"><label for="activities">Хобі/Інтереси:</label></td>
                    <td><textarea id="activities" name="activities" rows="3" cols="40"><?= e($student['activities'] ?? '') ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label>
                            <input type="checkbox" name="has_experience" value="1" <?= $student['has_experience'] ? 'checked' : '' ?>>
                            Має досвід роботи
                        </label>
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset>
            <legend><strong>Дані батьків</strong></legend>
            <?php foreach (['father' => 'Батько', 'mother' => 'Мати'] as $role => $label):
                $p = $parents[$role]; ?>
                <div>
                    <h3><?= $label ?></h3>
                    <input type="hidden" name="parents[<?= $role ?>][id]" value="<?= e($p['id']) ?>">
                    <input type="hidden" name="parents[<?= $role ?>][type]" value="<?= $role ?>">
                    <table class="form-table">
                        <tr>
                            <td><label>ПІБ:</label></td>
                            <td><input type="text" name="parents[<?= $role ?>][full_name]" value="<?= e($p['full_name']) ?>" size="50"></td>
                        </tr>
                        <tr>
                            <td><label>Місце роботи:</label></td>
                            <td><input type="text" name="parents[<?= $role ?>][work_info]" value="<?= e($p['work_info'] ?? '') ?>" size="50"></td>
                        </tr>
                        <tr>
                            <td><label>Телефон:</label></td>
                            <td>
                                <div class="phone-field-wrap">
                                    <?php [$pPfx, $pNum] = phonePrefix($p['phone'] ?? ''); ?>
                                    <select name="parents[<?= $role ?>][phone_prefix]" class="phone-prefix-select">
                                        <?= phonePrefixOptions($pPfx) ?>
                                    </select>
                                    <input type="tel" name="parents[<?= $role ?>][phone_number]" class="phone-number-input"
                                        placeholder="50 123 45 67" value="<?= e($pNum) ?>">
                                </div>
                                <input type="hidden" name="parents[<?= $role ?>][phone]" value="<?= e($p['phone'] ?? '') ?>">
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <div class="form-actions">
            <button type="submit"><strong>Зберегти зміни</strong></button>
            <button type="button" onclick="window.location.href='index.php'">Назад до списку</button>
        </div>
    </form>
</main>

<?php require 'blocks/phone_script.php'; ?>
<?php require 'blocks/footer.php'; ?>