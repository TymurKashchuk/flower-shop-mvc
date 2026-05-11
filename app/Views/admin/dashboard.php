<?php /** @var array $stats */ ?>

<div class="admin-page-header">
    <h2 class="admin-page-title">Огляд</h2>
    <p class="admin-page-sub">Загальна статистика магазину</p>
</div>

<div class="admin-stats">
    <div class="admin-stat-card">
        <span class="admin-stat-card__value"><?= (int)$stats['products'] ?></span>
        <span class="admin-stat-card__label">Товарів</span>
    </div>
    <div class="admin-stat-card">
        <span class="admin-stat-card__value"><?= (int)$stats['news'] ?></span>
        <span class="admin-stat-card__label">Новин</span>
    </div>
    <div class="admin-stat-card">
        <span class="admin-stat-card__value"><?= (int)$stats['reviews'] ?></span>
        <span class="admin-stat-card__label">Відгуків</span>
    </div>
    <div class="admin-stat-card admin-stat-card--accent">
        <span class="admin-stat-card__value"><?= (int)$stats['pending_reviews'] ?></span>
        <span class="admin-stat-card__label">Очікують модерації</span>
    </div>
    <div class="admin-stat-card">
        <span class="admin-stat-card__value"><?= (int)$stats['orders'] ?></span>
        <span class="admin-stat-card__label">Замовлень</span>
    </div>
    <div class="admin-stat-card admin-stat-card--accent">
        <span class="admin-stat-card__value"><?= (int)$stats['new_orders'] ?></span>
        <span class="admin-stat-card__label">Нових замовлень</span>
    </div>
</div>

<div class="admin-section-title">Швидкі дії</div>
<div class="admin-quick-links">
    <a href="<?= BASE_URL ?>/admin/news/create" class="btn btn-primary">+ Додати новину</a>
    <a href="<?= BASE_URL ?>/admin/reviews" class="btn btn-ghost">Переглянути відгуки</a>
    <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-ghost">Переглянути замовлення</a>
</div>