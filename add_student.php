<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Додати нового студента";
require 'blocks/header.php';
?>

<main>
    <nav>
        <a href="main.php">Назад до списку</a>
    </nav>

    <h1>Нова картка студента</h1>

    <form action="logic/insert_student.php" method="POST" id="studentForm">
        <section>
            <fieldset>
                <legend><strong>Дані студента</strong></legend>

                <p>
                    <label for="full_name">ПІБ Студента:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </p>

                <p>
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone">
                </p>

                <p>
                    <label for="birth_date">Дата народження:</label>
                    <input type="date" id="birth_date" name="birth_date">
                </p>

                <p>
                    <label for="home_address">Адреса реєстрації:</label>
                    <input type="text" id="home_address" name="home_address">
                </p>

                <p>
                    <label for="actual_address">Фактична адреса:</label>
                    <input type="text" id="actual_address" name="actual_address">
                </p>

                <p>
                    <label for="education">Освіта:</label>
                    <input type="text" id="education" name="education">
                </p>

                <p>
                    <label for="languages">Мови:</label>
                    <input type="text" id="languages" name="languages">
                </p>

                <p>
                    <label for="info_source">Джерело інформації:</label>
                    <input type="text" id="info_source" name="info_source">
                </p>

                <p>
                    <label for="career_goal">Кар'єрна ціль:</label>
                    <input type="text" id="career_goal" name="career_goal">
                </p>

                <p>
                    <label for="programming_languages">Мови програмування:</label>
                    <input type="text" id="programming_languages" name="programming_languages">
                </p>

                <p>
                    <label for="activities">Хобі/Інтереси:</label>
                    <textarea id="activities" name="activities" rows="3"></textarea>
                </p>

                <p>
                    <label>
                        <input type="checkbox" name="has_experience" value="1">
                        Має досвід роботи
                    </label>
                </p>
            </fieldset>
        </section>

        <section>
            <fieldset>
                <legend><strong>Дані батьків</strong></legend>
                <div id="parents-container">
                    <article class="parent-entry">
                        <p>
                            <label for="p_full_name_0">ПІБ:</label>
                            <input type="text" id="p_full_name_0" name="p_full_name[]" required>
                        </p>
                        <p>
                            <label for="p_type_0">Тип (мати/батько/опікун):</label>
                            <select id="p_type_0" name="p_type[]">
                                <option value="мати">Мати</option>
                                <option value="батько">Батько</option>
                                <option value="опікун">Опікун</option>
                            </select>
                        </p>
                        <p>
                            <label for="p_work_info_0">Місце роботи:</label>
                            <input type="text" id="p_work_info_0" name="p_work_info[]">
                        </p>
                        <p>
                            <label for="p_phone_0">Телефон:</label>
                            <input type="tel" id="p_phone_0" name="p_phone[]">
                        </p>
                    </article>
                </div>                
            </fieldset>
            <button type="button" id="add-parent-btn">Додати ще одного батька/опікуна</button>
        </section>

        <button type="submit">Зберегти студента та батьків</button>
    </form>
</main>

<script>
    let parentCount = 1;

    document.getElementById('add-parent-btn').addEventListener('click', function() {
        const container = document.getElementById('parents-container');
        const newEntry = document.createElement('article');
        newEntry.className = 'parent-entry';

        newEntry.innerHTML = `
        <hr>
        <p>
            <label for="p_full_name_${parentCount}">ПІБ:</label>
            <input type="text" id="p_full_name_${parentCount}" name="p_full_name[]" required>
        </p>
        <p>
            <label for="p_type_${parentCount}">Тип (мати/батько/опікун):</label>
            <select id="p_type_${parentCount}" name="p_type[]">
                <option value="мати">Мати</option>
                <option value="батько">Батько</option>
                <option value="опікун">Опікун</option>
            </select>
        </p>
        <p>
            <label for="p_work_info_${parentCount}">Місце роботи:</label>
            <input type="text" id="p_work_info_${parentCount}" name="p_work_info[]">
        </p>
        <p>
            <label for="p_phone_${parentCount}">Телефон:</label>
            <input type="tel" id="p_phone_${parentCount}" name="p_phone[]">
        </p>
        <button type="button" onclick="this.parentElement.remove()">Видалити цього батька/опікуна</button>
    `;

        container.appendChild(newEntry);
        parentCount++;
    });
</script>

<?php require 'blocks/footer.php'; ?>