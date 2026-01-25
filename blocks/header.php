<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Система куратора' ?></title>
</head>

<body>
    <header>
        <h1><?= $pageTitle ?></h1>
        <nav>
            <a href="add_student.php">Додати студента</a> |
            <a href="change_password.php">Поміняти пароль</a> |
            <a href="logout.php">Вийти</a>
        </nav>
        
        <br>

        <form action="find_student.php" method="get" role="search">
            <label for="search-input">Пошук студента:</label>
            <input type="text" 
                   id="search-input" 
                   name="full-name" 
                   placeholder="Введіть ПІБ студента">
            <button type="submit">Знайти</button>
        </form>

        <hr>
    </header>