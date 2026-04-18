<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Додати нового студента";
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
                                <option value="+38" selected>🇺🇦 +380</option>
                                <option value="+48">🇵🇱 +48</option>
                                <option value="+49">🇩🇪 +49</option>
                                <option value="+44">🇬🇧 +44</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+33">🇫🇷 +33</option>
                                <option value="+39">🇮🇹 +39</option>
                                <option value="+34">🇪🇸 +34</option>
                                <option value="+40">🇷🇴 +40</option>
                                <option value="+36">🇭🇺 +36</option>
                                <option value="+420">🇨🇿 +420</option>
                                <option value="+421">🇸🇰 +421</option>
                                <option value="+372">🇪🇪 +372</option>
                                <option value="+371">🇱🇻 +371</option>
                                <option value="+370">🇱🇹 +370</option>
                            </select>
                            <input type="tel" id="phone_number" name="phone_number" class="phone-number-input" placeholder="50 123 45 67">
                        </div>
                        <input type="hidden" name="phone" id="phone_combined" class="phone-combined">
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
            <legend><strong>Батьки</strong></legend>
            
            <div style="margin-bottom: 15px;">
                <h3>Батько</h3>
                <input type="hidden" name="p_type[]" value="father">
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
                                <select class="phone-prefix-select">
                                    <option value="+38" selected>🇺🇦 +380</option>
                                    <option value="+48">🇵🇱 +48</option>
                                    <option value="+49">🇩🇪 +49</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+33">🇫🇷 +33</option>
                                    <option value="+39">🇮🇹 +39</option>
                                    <option value="+34">🇪🇸 +34</option>
                                    <option value="+40">🇷🇴 +40</option>
                                    <option value="+36">🇭🇺 +36</option>
                                    <option value="+420">🇨🇿 +420</option>
                                    <option value="+421">🇸🇰 +421</option>
                                    <option value="+372">🇪🇪 +372</option>
                                    <option value="+371">🇱🇻 +371</option>
                                    <option value="+370">🇱🇹 +370</option>
                                    <option value="+7">kz/ru +7</option>
                                </select>
                                <input type="text" class="phone-number-input" size="20">
                                <input type="hidden" name="p_phone[]" class="phone-combined">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <h3>Мати</h3>
                <input type="hidden" name="p_type[]" value="mother">
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
                                <select class="phone-prefix-select">
                                    <option value="+38" selected>🇺🇦 +380</option>
                                    <option value="+48">🇵🇱 +48</option>
                                    <option value="+49">🇩🇪 +49</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+33">🇫🇷 +33</option>
                                    <option value="+39">🇮🇹 +39</option>
                                    <option value="+34">🇪🇸 +34</option>
                                    <option value="+40">🇷🇴 +40</option>
                                    <option value="+36">🇭🇺 +36</option>
                                    <option value="+420">🇨🇿 +420</option>
                                    <option value="+421">🇸🇰 +421</option>
                                    <option value="+372">🇪🇪 +372</option>
                                    <option value="+371">🇱🇻 +371</option>
                                    <option value="+370">🇱🇹 +370</option>
                                    <option value="+7">kz/ru +7</option>
                                </select>
                                <input type="text" class="phone-number-input" size="20">
                                <input type="hidden" name="p_phone[]" class="phone-combined">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <br>
        <p>
            <button type="submit"><strong>Зберегти картку студента</strong></button>
        </p>
    </form>
</main>

<script>
document.querySelectorAll('.phone-field-wrap').forEach(function(wrap) {
    var prefix = wrap.querySelector('.phone-prefix-select');
    var number = wrap.querySelector('.phone-number-input');
    var combined = wrap.querySelector('.phone-combined');

    if (prefix && number && combined) {
        function update() {
            combined.value = number.value.trim() ? prefix.value + number.value.trim() : '';
        }
        prefix.addEventListener('change', update);
        number.addEventListener('input', update);
        update();
    }
});