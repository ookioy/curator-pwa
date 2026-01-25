<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$student_id = $_GET['student_id'] ?? null;
if (!$student_id) { 
    header('Location: main.php'); 
    exit; 
}

// Отримуємо студента
$stmt = $pdo->prepare("SELECT full_name FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) { 
    die("Студента не знайдено!"); 
}

// Отримуємо батьків
$stmt_parents = $pdo->prepare("SELECT * FROM parents WHERE student_id = ? ORDER BY type");
$stmt_parents->execute([$student_id]);
$parents = $stmt_parents->fetchAll();

$pageTitle = "Редагування батьків: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <nav>
        <a href="view_student.php?id=<?= $student_id ?>">Назад до профілю</a>
    </nav>
    
    <h1>Редагування батьків/опікунів</h1>

    <?php if (isset($_GET['updated'])): ?>
        <p class="message success">Зміни збережено!</p>
    <?php endif; ?>

    <section>
        <h3>Поточні батьки/опікуни</h3>

        <?php if (empty($parents)): ?>
            <p>Батьків ще не додано.</p>
        <?php else: ?>
            <?php foreach ($parents as $parent): ?>
                <article class="parent-card">
                    <form action="logic/update_parent.php" method="POST">
                        <input type="hidden" name="parent_id" value="<?= $parent['id'] ?>">
                        <input type="hidden" name="student_id" value="<?= $student_id ?>">
                        
                        <p>
                            <label for="full_name_<?= $parent['id'] ?>">ПІБ:</label>
                            <input type="text" 
                                   id="full_name_<?= $parent['id'] ?>" 
                                   name="full_name" 
                                   value="<?= htmlspecialchars($parent['full_name']) ?>" 
                                   required>
                        </p>
                        
                        <p>
                            <label for="type_<?= $parent['id'] ?>">Тип:</label>
                            <select id="type_<?= $parent['id'] ?>" name="type">
                                <option value="мати" <?= $parent['type'] === 'мати' ? 'selected' : '' ?>>Мати</option>
                                <option value="батько" <?= $parent['type'] === 'батько' ? 'selected' : '' ?>>Батько</option>
                                <option value="опікун" <?= $parent['type'] === 'опікун' ? 'selected' : '' ?>>Опікун</option>
                            </select>
                        </p>
                        
                        <p>
                            <label for="work_info_<?= $parent['id'] ?>">Місце роботи:</label>
                            <input type="text" 
                                   id="work_info_<?= $parent['id'] ?>" 
                                   name="work_info" 
                                   value="<?= htmlspecialchars($parent['work_info'] ?? '') ?>">
                        </p>
                        
                        <p>
                            <label for="phone_<?= $parent['id'] ?>">Телефон:</label>
                            <input type="tel" 
                                   id="phone_<?= $parent['id'] ?>" 
                                   name="phone" 
                                   value="<?= htmlspecialchars($parent['phone'] ?? '') ?>">
                        </p>
                        
                        <button type="submit">Оновити</button>
                    </form>
                    
                    <form action="logic/delete_parent.php" method="POST" onsubmit="return confirm('Видалити цього батька/опікуна?');">
                        <input type="hidden" name="parent_id" value="<?= $parent['id'] ?>">
                        <input type="hidden" name="student_id" value="<?= $student_id ?>">
                        <button type="submit">Видалити</button>
                    </form>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section>
        <h3>Додати нового батька/опікуна</h3>
        <form action="logic/add_parent.php" method="POST">
            <input type="hidden" name="student_id" value="<?= $student_id ?>">
            
            <p>
                <label for="new_full_name">ПІБ:</label>
                <input type="text" id="new_full_name" name="full_name" required>
            </p>
            
            <p>
                <label for="new_type">Тип:</label>
                <select id="new_type" name="type">
                    <option value="мати">Мати</option>
                    <option value="батько">Батько</option>
                    <option value="опікун">Опікун</option>
                </select>
            </p>
            
            <p>
                <label for="new_work_info">Місце роботи:</label>
                <input type="text" id="new_work_info" name="work_info">
            </p>
            
            <p>
                <label for="new_phone">Телефон:</label>
                <input type="tel" id="new_phone" name="phone">
            </p>
            
            <button type="submit">Додати</button>
        </form>
    </section>
</main>

<?php require 'blocks/footer.php'; ?>