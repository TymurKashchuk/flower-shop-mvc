<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= BASE_URL ?>">
    <title><?= htmlspecialchars($title ?? 'Квітковий салон') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Inter:wght@400;500&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container header__inner">

        <!-- Логотип -->
        <a href="<?= BASE_URL ?>" class="logo" aria-label="Квітковий салон — на головну">
            <svg class="logo__icon" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                <circle cx="16" cy="16" r="4" fill="currentColor"/>
                <ellipse cx="16" cy="8" rx="3" ry="5" fill="currentColor" opacity=".7"/>
                <ellipse cx="16" cy="24" rx="3" ry="5" fill="currentColor" opacity=".7"/>
                <ellipse cx="8" cy="16" rx="5" ry="3" fill="currentColor" opacity=".7"/>
                <ellipse cx="24" cy="16" rx="5" ry="3" fill="currentColor" opacity=".7"/>
                <ellipse cx="10" cy="10" rx="3" ry="5" fill="currentColor" opacity=".5" transform="rotate(-45 10 10)"/>
                <ellipse cx="22" cy="10" rx="3" ry="5" fill="currentColor" opacity=".5" transform="rotate(45 22 10)"/>
                <ellipse cx="10" cy="22" rx="3" ry="5" fill="currentColor" opacity=".5" transform="rotate(45 10 22)"/>
                <ellipse cx="22" cy="22" rx="3" ry="5" fill="currentColor" opacity=".5" transform="rotate(-45 22 22)"/>
            </svg>
            <span class="logo__text">Квітковий салон</span>
        </a>

        <nav class="nav" aria-label="Основна навігація">
            <a href="<?= BASE_URL ?>/catalog">Каталог</a>
            <a href="<?= BASE_URL ?>/news">Новини</a>
            <a href="<?= BASE_URL ?>/gallery">Галерея</a>
        </nav>

        <div class="header-actions">

            <!-- Пошук -->
            <div class="search-wrap" role="search">
                <label for="search-input" class="sr-only">Пошук квітів</label>
                <svg class="search-wrap__icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <circle cx="8.5" cy="8.5" r="5.5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M13 13l3.5 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <input
                        type="search"
                        id="search-input"
                        placeholder="Пошук квітів..."
                        autocomplete="off"
                />
                <div id="search-results" class="search-dropdown" role="listbox" aria-label="Результати пошуку"></div>
            </div>

            <!-- Кошик -->
            <a href="<?= BASE_URL ?>/cart" class="cart-btn" aria-label="Кошик">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke="currentColor" stroke-width="1.5"
                          stroke-linejoin="round"/>
                    <path d="M3 6h18M16 10a4 4 0 01-8 0" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round"/>
                </svg>
                <span class="cart-count" aria-live="polite">0</span>
            </a>

            <!-- Авторизація -->
            <?php if (!empty($_SESSION['user'])): ?>
                <span class="user-name">
                    <?= htmlspecialchars($_SESSION['user']['name']) ?>
                </span>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin" class="btn btn-admin">Адмінка</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/logout" class="btn btn-ghost">Вийти</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="btn btn-ghost">Увійти</a>
                <a href="<?= BASE_URL ?>/register" class="btn btn-primary">Реєстрація</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="main" id="main-content">
    <div class="container">

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </div>
</main>

<footer class="footer">
    <div class="container footer__inner">
        <p class="footer__copy">&copy; <?= date('Y') ?> Квітковий салон. Всі права захищені.</p>
        <address class="footer__address">
            <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <path d="M10 2a6 6 0 016 6c0 4-6 10-6 10S4 12 4 8a6 6 0 016-6z" stroke="currentColor"
                      stroke-width="1.5"/>
                <circle cx="10" cy="8" r="2" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            м. Житомир &nbsp;|&nbsp;
            <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <path d="M3 3h4l2 4-2 2a12 12 0 005 5l2-2 4 2v4a2 2 0 01-2 2C7 19 1 13 1 5a2 2 0 012-2z"
                      stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
            </svg>
            <a href="tel:+380670000000">+38 (067) 000-00-00</a>
        </address>
    </div>
</footer>

<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
</body>
</html>