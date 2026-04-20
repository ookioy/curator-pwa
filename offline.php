<?php
$pageTitle = 'Немає з\'єднання';
require 'blocks/header.php';
?>
<main style="text-align:center; padding: 4rem 1.5rem;">
    <div style="font-size:3rem; margin-bottom:1rem;">📡</div>
    <h2>Немає з'єднання з мережею</h2>
    <p>Перевірте підключення до інтернету та спробуйте ще раз.</p>
    <p><button onclick="window.location.reload()">Оновити сторінку</button></p>
</main>
<?php require 'blocks/footer.php'; ?>