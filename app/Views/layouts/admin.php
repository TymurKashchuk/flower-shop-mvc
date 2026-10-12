<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$currentUri = rtrim($currentUri, '/');
$adminPrefix = parse_url(BASE_URL . '/admin', PHP_URL_PATH);

$isDashboard = ($currentUri === $adminPrefix || $currentUri === '/admin');
$isNews = str_contains($currentUri, '/admin/news');
$isReviews = str_contains($currentUri, '/admin/reviews');
$isProducts = str_contains($currentUri, '/admin/products');
$isOrders = str_contains($currentUri, '/admin/orders');
$isUsers = str_contains($currentUri, '/admin/users');

// Dynamic counter badges for sidebar
$pendingReviewsCount = 0;
$newOrdersCount = 0;
try {
    $db = \core\Database::getInstance();
    $pendingReviewsCount = (int)$db->query("SELECT COUNT(*) FROM reviews WHERE is_active = 0")->fetchColumn();
    $newOrdersCount = (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'new'")->fetchColumn();
} catch (\Throwable $e) {
    // Fail silently if database is temporarily unavailable
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Адмін-панель') ?> — Квітковий салон</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= filemtime(ROOT_PATH . '/public/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css?v=<?= filemtime(ROOT_PATH . '/public/assets/css/admin.css') ?>">
</head>
<body class="admin-body">

<!-- Mobile Backdrop -->
<div class="admin-backdrop" id="adminBackdrop"></div>

<div class="admin-layout">

    <!-- Sidebar Drawer -->
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="<?= BASE_URL ?>/admin" class="admin-sidebar__logo">
            <div class="admin-sidebar__logo-icon">
                <svg viewBox="0 0 32 32" fill="none" aria-hidden="true" width="22" height="22">
                    <circle cx="16" cy="16" r="4" fill="currentColor"/>
                    <ellipse cx="16" cy="8" rx="3" ry="5" fill="currentColor" opacity=".8"/>
                    <ellipse cx="16" cy="24" rx="3" ry="5" fill="currentColor" opacity=".8"/>
                    <ellipse cx="8" cy="16" rx="5" ry="3" fill="currentColor" opacity=".8"/>
                    <ellipse cx="24" cy="16" rx="5" ry="3" fill="currentColor" opacity=".8"/>
                </svg>
            </div>
            <div class="admin-sidebar__logo-text">
                <span class="admin-sidebar__logo-title">Квітковий салон</span>
                <span class="admin-sidebar__logo-sub">Панель керування</span>
            </div>
        </a>

        <nav class="admin-nav">
            <span class="admin-nav__label">Головне</span>
            <a href="<?= BASE_URL ?>/admin"
               class="admin-nav__item <?= $isDashboard ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <rect x="2" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                    <rect x="11" y="2" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                    <rect x="2" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                    <rect x="11" y="11" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <span>Дашборд</span>
            </a>

            <span class="admin-nav__label">Магазин</span>
            <a href="<?= BASE_URL ?>/admin/orders"
               class="admin-nav__item <?= $isOrders ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <rect x="3" y="4" width="14" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M6 8h8M6 11h8M6 14h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span>Замовлення</span>
                <?php if ($newOrdersCount > 0): ?>
                    <span class="admin-nav__badge admin-nav__badge--primary" title="Нових замовлень">
                        +<?= $newOrdersCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="<?= BASE_URL ?>/admin/products"
               class="admin-nav__item <?= $isProducts ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M3 6h14M3 10h14M3 14h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span>Товари</span>
            </a>

            <span class="admin-nav__label">Контент і клієнти</span>
            <a href="<?= BASE_URL ?>/admin/reviews"
               class="admin-nav__item <?= $isReviews ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M10 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4L10 14.4l-4.8 2.5.9-5.4L2.2 7.7l5.4-.8z"
                          stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                </svg>
                <span>Відгуки</span>
                <?php if ($pendingReviewsCount > 0): ?>
                    <span class="admin-nav__badge" title="Очікують схвалення">
                        <?= $pendingReviewsCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="<?= BASE_URL ?>/admin/news"
               class="admin-nav__item <?= $isNews ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M4 4h12M4 8h12M4 12h7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <rect x="2" y="2" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <span>Новини</span>
            </a>

            <a href="<?= BASE_URL ?>/admin/users"
               class="admin-nav__item <?= $isUsers ? 'admin-nav__item--active' : '' ?>">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M18 17a6 6 0 10-12 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span>Користувачі</span>
            </a>
        </nav>

        <div class="admin-sidebar__footer">
            <a href="<?= BASE_URL ?>" class="admin-nav__item" target="_blank" rel="noopener noreferrer">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M10 3L3 9v8h5v-5h4v5h5V9z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                </svg>
                <span>Перейти на сайт</span>
            </a>
            <a href="<?= BASE_URL ?>/logout" class="admin-nav__item admin-nav__item--danger">
                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Вийти з акаунта</span>
            </a>
        </div>
    </aside>

    <!-- Main content area -->
    <div class="admin-main">
        <header class="admin-header">
            <div class="admin-header__left">
                <button type="button" class="admin-header__menu-btn" id="adminSidebarToggle" aria-label="Відкрити меню">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h1 class="admin-header__title"><?= htmlspecialchars($title ?? 'Адміністративна панель') ?></h1>
            </div>

            <div class="admin-header__right">
                <a href="<?= BASE_URL ?>" class="admin-header__site-link" target="_blank" rel="noopener noreferrer">
                    <span>На вітрину</span>
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M11 3h6v6M10 10l7-7M17 11v6H3V3h6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="admin-header__user">
                    <div class="admin-header__avatar">
                        <?= mb_strtoupper(mb_substr($_SESSION['user']['name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="admin-header__user-info">
                        <span class="admin-header__user-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Адміністратор') ?></span>
                        <span class="admin-header__user-role">Адміністратор</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="admin-alert admin-alert--success">
                    <svg class="admin-alert__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="admin-alert__text"><?= htmlspecialchars($_SESSION['success']) ?></span>
                    <button type="button" class="admin-alert__close" aria-label="Закрити">&times;</button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="admin-alert admin-alert--error">
                    <svg class="admin-alert__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="admin-alert__text"><?= htmlspecialchars($_SESSION['error']) ?></span>
                    <button type="button" class="admin-alert__close" aria-label="Закрити">&times;</button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </main>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
<script src="<?= BASE_URL ?>/assets/js/admin.js?v=<?= filemtime(ROOT_PATH . '/public/assets/js/admin.js') ?>"></script>
</body>
</html>