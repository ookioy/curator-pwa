<?php
require 'logic/db.php';
require 'logic/auth.php';
require 'logic/helpers.php';

protectPage($pdo);

$pageTitle = 'Зміна пароля';
$pageCss   = 'change_password.css';
require 'blocks/header.php';

$errors = [
    'current' => 'Поточний пароль невірний!',
    'length'  => 'Новий пароль повинен містити мінімум 6 символів!',
    'match'   => 'Новий пароль та підтвердження не збігаються!',
    'same'    => 'Новий пароль не може бути таким самим як старий!',
    'db'      => 'Помилка бази даних при зміні пароля!',
];
?>

<main>
    <a href="index.php" class="back-link">&larr; Назад до списку</a>
    <h2>Зміна пароля</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>Пароль успішно змінено!</strong> З міркувань безпеки всі активні сесії скинуто. Будь ласка, увійдіть заново.
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && isset($errors[$_GET['error']])): ?>
        <p class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <strong>Помилка:</strong> <?= e($errors[$_GET['error']]) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="logic/update_password.php" class="password-form">
        <fieldset>
            <legend><strong>Зміна пароля адміністратора</strong></legend>
            <p>
                <label for="current_password">Поточний пароль:</label>
                <input type="password" id="current_password" name="current_password" size="30" required autofocus>
            </p>
            <p>
                <label for="new_password">Новий пароль:</label>
                <input type="password" id="new_password" name="new_password" size="30" required minlength="6">
                <small>(Мінімум 6 символів)</small>
            </p>
            <p>
                <label for="confirm_password">Підтвердіть новий пароль:</label>
                <input type="password" id="confirm_password" name="confirm_password" size="30" required minlength="6">
            </p>
            <p><button type="submit">Змінити пароль</button></p>
        </fieldset>
    </form>

    <fieldset>
        <legend><strong>Важливо:</strong></legend>
        <ul>
            <li>Після зміни пароля всі активні сесії будуть завершені</li>
            <li>Вам потрібно буде увійти заново з новим паролем</li>
            <li>Збережіть новий пароль у безпечному місці</li>
        </ul>
    </fieldset>
</main>

<?php require 'blocks/footer.php'; ?>