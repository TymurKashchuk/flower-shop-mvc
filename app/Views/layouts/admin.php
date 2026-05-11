<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Адмін-панель') ?> — Квітковий салон</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Inter:wght@400;500&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="admin-body">

<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <a href="<?= BASE_URL ?>" class="admin-sidebar__logo">
            <svg viewBox="0 0 32 32" fill="none" aria-hidden="true" width="28" height="28">
                <circle cx="16" cy="16" r="4" fill="currentColor"/>
                <ellipse cx="16" cy="8" rx="3" ry="5" fill="currentColor" opacity=".7"/>
                <ellipse cx="16" cy="24" rx="3" ry="5" fill="currentColor" opacity=".7"/>
                <ellipse cx="8" cy="16" rx="5" ry="3" fill="currentColor" opacity=".7"/>
                <ellipse cx="24" cy="16" rx="5" ry="3" fill="currentColor" opacity=".7"/>
            </svg>
            <span>Адмін-панель</span>
        </a>

        <nav class="admin-nav">
            <a href="<?= BASE_URL ?>/admin"
               class="admin-nav__item <?= str_ends_with($_SERVER['REQUEST_URI'], '/admin') ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <rect x="2" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                    <rect x="11" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                    <rect x="2" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                    <rect x="11" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                Дашборд
            </a>

            <a href="<?= BASE_URL ?>/admin/news"
               class="admin-nav__item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/news') ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M4 4h12M4 8h12M4 12h7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <rect x="2" y="2" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                Новини
            </a>

            <a href="<?= BASE_URL ?>/admin/reviews"
               class="admin-nav__item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reviews') ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M10 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4L10 14.4l-4.8 2.5.9-5.4L2.2 7.7l5.4-.8z"
                          stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                </svg>
                Відгуки
            </a>

            <a href="<?= BASE_URL ?>/admin/products"
               class="admin-nav__item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/products') ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M3 6h14M3 10h14M3 14h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Товари
            </a>

            <a href="<?= BASE_URL ?>/admin/orders"
               class="admin-nav__item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/orders') ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <rect x="3" y="4" width="14" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M6 8h8M6 11h8M6 14h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Замовлення
            </a>

            <a href="<?= BASE_URL ?>/admin/users"
               class="admin-nav__item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0z"
                          stroke="currentColor" stroke-width="1.5"/>
                    <path d="M18 17a6 6 0 10-12 0"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Користувачі
            </a>
        </nav>

        <div class="admin-sidebar__footer">
            <a href="<?= BASE_URL ?>" class="admin-nav__item">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M10 3L3 9v8h5v-5h4v5h5V9z" stroke="currentColor" stroke-width="1.5"
                          stroke-linejoin="round"/>
                </svg>
                На сайт
            </a>
            <a href="<?= BASE_URL ?>/logout" class="admin-nav__item admin-nav__item--danger">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Вийти
            </a>
        </div>
    </aside>

    <!-- Main content -->
    <div class="admin-main">
        <header class="admin-header">
            <h1 class="admin-header__title"><?= htmlspecialchars($title ?? '') ?></h1>
            <div class="admin-header__user">
                <div class="user-menu__avatar" style="width:34px;height:34px;font-size:0.875rem">
                    <?= mb_strtoupper(mb_substr($_SESSION['user']['name'] ?? 'A', 0, 1)) ?>
                </div>
                <span style="font-size:var(--text-sm);color:var(--color-text-muted)">
                    <?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>
                </span>
            </div>
        </header>

        <div class="admin-content">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
</body>
</html>