<?php
require 'db.php';
require 'auth.php';

// Захист: тільки авторизовані можуть оновлювати
protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо ID (передамо його через hidden input)
    $id = $_POST['id'] ?? null;
    
    if ($id) {
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $home_address = $_POST['home_address'];
        $additional_info = $_POST['additional_info'];

        $sql = "UPDATE students 
                SET full_name = :full_name, 
                    phone = :phone, 
                    email = :email, 
                    home_address = :home_address, 
                    additional_info = :additional_info 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'full_name'       => $full_name,
            'phone'           => $phone,
            'email'           => $email,
            'home_address'    => $home_address,
            'additional_info' => $additional_info,
            'id'              => $id
        ]);

        // Повертаємо назад на сторінку перегляду з прапорцем успіху
        header("Location: view_student.php?id=$id&updated=1");
        exit;
    }
}

// Якщо хтось зайшов на цей файл просто так — виганяємо на головну
header('Location: main.php');
exit;