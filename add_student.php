<?php
require 'logic/db.php';
require 'logic/auth.php';
require 'logic/helpers.php';

protectPage($pdo);

$pageTitle = 'Додати нового студента';
require 'blocks/header.php';
?>

<main>
    <p><a href="index.php">&larr; Назад до списку</a></p>
    <h2>Нова картка студента</h2>

    <form action="logic/insert_student.php" method="POST" id="studentForm">

        <fieldset>
            <legend><strong>Дані студента</strong></legend>
            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td><label for="full_name">ПІБ Студента: <em>*</em></label></td>
                    <td><input type="text" id="full_name" name="full_name" size="40" required></td>
                </tr>
                <tr>
                    <td><label for="phone_number">Телефон:</label></td>
                    <td>
                        <div class="phone-field-wrap">
                            <select name="phone_prefix" id="phone_prefix" class="phone-prefix-select">
                                <?= phonePrefixOptions() ?>
                            </select>
                            <input type="tel" id="phone_number" name="phone_number" class="phone-number-input" placeholder="50 123 45 67">
                        </div>
                        <input type="hidden" name="phone" id="phone_combined">
                    </td>
                </tr>
                <tr>
                    <td><label for="birth_date">Дата народження:</label></td>
                    <td><input type="date" id="birth_date" name="birth_date"></td>
                </tr>
                <tr>
                    <td><label for="home_address">Адреса реєстрації:</label></td>
                    <td><input type="text" id="home_address" name="home_address" size="40"></td>
                </tr>
                <tr>
                    <td><label for="actual_address">Фактична адреса:</label></td>
                    <td><input type="text" id="actual_address" name="actual_address" size="40"></td>
                </tr>
                <tr>
                    <td><label for="education">Освіта:</label></td>
                    <td><input type="text" id="education" name="education" size="40"></td>
                </tr>
                <tr>
                    <td><label for="languages">Мови:</label></td>
                    <td><input type="text" id="languages" name="languages" size="40"></td>
                </tr>
                <tr>
                    <td><label for="info_source">Джерело інформації:</label></td>
                    <td><input type="text" id="info_source" name="info_source" size="40"></td>
                </tr>
                <tr>
                    <td><label for="career_goal">Кар'єрна ціль:</label></td>
                    <td><input type="text" id="career_goal" name="career_goal" size="40"></td>
                </tr>
                <tr>
                    <td><label for="programming_languages">Мови програмування:</label></td>
                    <td><input type="text" id="programming_languages" name="programming_languages" size="40"></td>
                </tr>
                <tr>
                    <td valign="top"><label for="activities">Хобі/Інтереси:</label></td>
                    <td><textarea id="activities" name="activities" rows="3" cols="40"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label>
                            <input type="checkbox" name="has_experience" value="1"> Має досвід роботи
                        </label>
                    </td>
                </tr>
            </table>
        </fieldset>

        <br>

        <fieldset>
            <legend><strong>Дані батьків</strong></legend>
            <?php foreach (['father' => 'Батько', 'mother' => 'Мати'] as $type => $label): ?>
                <div>
                    <h3><?= $label ?></h3>
                    <input type="hidden" name="p_type[]" value="<?= $type ?>">
                    <table border="0" cellpadding="5">
                        <tr>
                            <td><label>ПІБ:</label></td>
                            <td><input type="text" name="p_full_name[]" size="50"></td>
                        </tr>
                        <tr>
                            <td><label>Місце роботи:</label></td>
                            <td><input type="text" name="p_work_info[]" size="50"></td>
                        </tr>
                        <tr>
                            <td><label>Телефон:</label></td>
                            <td>
                                <div class="phone-field-wrap">
                                    <select name="p_phone_prefix[]" class="phone-prefix-select">
                                        <?= phonePrefixOptions('+380') ?>
                                    </select>
                                    <input type="tel" name="p_phone_number[]" class="phone-number-input" placeholder="50 123 45 67">
                                </div>
                                <input type="hidden" name="p_phone[]" class="p_phone_combined">
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <br>
        <p>
            <button type="submit"><strong>Зберегти картку студента</strong></button>
        </p>
    </form>
</main>

<?php require 'blocks/phone_script.php'; ?>
<?php require 'blocks/footer.php'; ?>