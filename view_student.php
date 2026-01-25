<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: main.php');
    exit;
}

// Отримуємо дані студента
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Студента не знайдено!");
}

// Отримуємо батьків студента
$stmt_parents = $pdo->prepare("SELECT * FROM parents WHERE student_id = ? ORDER BY type");
$stmt_parents->execute([$id]);
$parents = $stmt_parents->fetchAll();

// Функція для валідації телефону
function isValidPhone($phone)
{
    if (empty($phone)) return false;
    // Прибираємо всі символи крім цифр та +
    $cleaned = preg_replace('/[^\d+]/', '', $phone);
    // Перевіряємо чи є достатньо цифр (мінімум 10)
    return strlen($cleaned) >= 10;
}

// Функція для форматування телефону для дзвінка
function formatPhoneForCall($phone)
{
    return 'tel:' . preg_replace('/[^\d+]/', '', $phone);
}

$pageTitle = "Профіль: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <nav>
        <a href="main.php">До списку</a>
    </nav>

    <h1>Перегляд та редагування студента</h1>

    <?php if (isset($_GET['updated'])): ?>
        <p class="message success">Зміни збережено!</p>
    <?php endif; ?>

    <form action="logic/update_student.php" method="POST" onsubmit="return confirm('Зберегти зміни?');">

        <input type="hidden" name="id" value="<?= $student['id'] ?>">

        <section>
            <fieldset>
                <legend><strong>Дані студента</strong></legend>

                <p>
                    <label for="full_name">ПІБ:</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>
                </p>

                <p>
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
                    <?php if (!empty($student['phone'])): ?>
                        <?php if (isValidPhone($student['phone'])): ?>
                            <a href="<?= formatPhoneForCall($student['phone']) ?>">
                                <button type="button">Зателефонувати</button>
                            </a>
                        <?php else: ?>
                            <button type="button" onclick="alert('Номер телефону не є дійсним!')">Зателефонувати</button>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>

                <p>
                    <label for="birth_date">Дата народження:</label>
                    <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($student['birth_date'] ?? '') ?>">
                </p>

                <p>
                    <label for="home_address">Адреса реєстрації:</label>
                    <input type="text" id="home_address" name="home_address" value="<?= htmlspecialchars($student['home_address'] ?? '') ?>">
                </p>

                <p>
                    <label for="actual_address">Фактична адреса:</label>
                    <input type="text" id="actual_address" name="actual_address" value="<?= htmlspecialchars($student['actual_address'] ?? '') ?>">
                </p>

                <p>
                    <label for="education">Освіта:</label>
                    <input type="text" id="education" name="education" value="<?= htmlspecialchars($student['education'] ?? '') ?>">
                </p>

                <p>
                    <label for="languages">Мови:</label>
                    <input type="text" id="languages" name="languages" value="<?= htmlspecialchars($student['languages'] ?? '') ?>">
                </p>

                <p>
                    <label for="info_source">Джерело інформації:</label>
                    <input type="text" id="info_source" name="info_source" value="<?= htmlspecialchars($student['info_source'] ?? '') ?>">
                </p>

                <p>
                    <label for="career_goal">Кар'єрна ціль:</label>
                    <input type="text" id="career_goal" name="career_goal" value="<?= htmlspecialchars($student['career_goal'] ?? '') ?>">
                </p>

                <p>
                    <label for="programming_languages">Мови програмування:</label>
                    <input type="text" id="programming_languages" name="programming_languages" value="<?= htmlspecialchars($student['programming_languages'] ?? '') ?>">
                </p>

                <p>
                    <label for="activities">Хобі/Інтереси:</label>
                    <textarea id="activities" name="activities" rows="3"><?= htmlspecialchars($student['activities'] ?? '') ?></textarea>
                </p>

                <p>
                    <label>
                        <input type="checkbox" name="has_experience" value="1" <?= $student['has_experience'] ? 'checked' : '' ?>>
                        Має досвід роботи
                    </label>
                </p>

                <button type="submit">Зберегти зміни студента</button>
            </fieldset>
        </section>
    </form>

    <section>
        <fieldset>
            <legend><strong>Батьки/Опікуни</strong></legend>

            <?php if (empty($parents)): ?>
                <p>Дані батьків не додано.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ПІБ</th>
                            <th>Тип</th>
                            <th>Місце роботи</th>
                            <th>Телефон</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parents as $parent): ?>
                            <tr>
                                <td><?= htmlspecialchars($parent['full_name']) ?></td>
                                <td><?= htmlspecialchars($parent['type']) ?></td>
                                <td><?= htmlspecialchars($parent['work_info'] ?? '') ?></td>
                                <td>
                                    <?= htmlspecialchars($parent['phone'] ?? '') ?>
                                    <?php if (!empty($parent['phone'])): ?>
                                        <br>
                                        <?php if (isValidPhone($parent['phone'])): ?>
                                            <a href="<?= formatPhoneForCall($parent['phone']) ?>">
                                                <button type="button">Зателефонувати</button>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" onclick="alert('Номер телефону не є дійсним!')">Зателефонувати</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <p>
                <a href="edit_parents.php?student_id=<?= $student['id'] ?>">
                    <button type="button">Редагувати батьків/опікунів</button>
                </a>
            </p>
        </fieldset>
    </section>
</main>

<?php require 'blocks/footer.php'; ?>