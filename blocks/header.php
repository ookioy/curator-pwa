<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Система куратора') ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='18' fill='%232e6b57'/><text y='72' x='50' text-anchor='middle' font-size='60' font-family='sans-serif'>🎓</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/curator-pwa/style.css">
</head>

<body>
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="header-brand">
                <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                <span class="brand-text">Система куратора</span>
            </a>

            <nav class="header-nav" aria-label="Головне меню">
                <a href="add_student.php" class="nav-link" title="Додати студента">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Додати студента</span>
                </a>
                <a href="change_password.php" class="nav-link" title="Змінити пароль">
                    <i class="fa-solid fa-lock"></i>
                    <span>Пароль</span>
                </a>
                <a href="logic/logout.php" class="nav-link nav-logout" title="Вийти">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Вийти</span>
                </a>
            </nav>
        </div>

        <div class="header-search">
            <form action="find_student.php" method="get" role="search">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i>
                    <input type="text"
                           id="search-input"
                           name="full-name"
                           placeholder="Пошук студента за ПІБ…"
                           autocomplete="off"
                           aria-label="Пошук студента">
                    <button type="submit">Знайти</button>
                </div>
            </form>
        </div>
    </header>