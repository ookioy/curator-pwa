<?php
require_once 'logic/helpers.php';
$pageTitle = 'Немає з\'єднання';
require 'blocks/header.php';
?>
<main class="offline-page">
    <div class="offline-icon">📡</div>
    <h2>Немає з'єднання з мережею</h2>
    <p>Перевірте підключення до інтернету та спробуйте ще раз.</p>
    <p><button onclick="window.location.reload()">Оновити сторінку</button></p>
</main>
<?php require 'blocks/footer.php'; ?>