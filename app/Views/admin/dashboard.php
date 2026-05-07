<?php /** @var array $stats */ ?>

<div class="admin-stats">
    <div class="admin-stat-card">
        <span class="admin-stat-card__value"><?= $stats['products'] ?></span>
        <span class="admin-stat-card__label">Товарів</span>
    </div>
    <div class="admin-stat-card">
        <span class="admin-stat-card__value"><?= $stats['news'] ?></span>
        <span class="admin-stat-card__label">Новин</span>
    </div>
    <div class="admin-stat-card">
        <span class="admin-stat-card__value"><?= $stats['reviews'] ?></span>
        <span class="admin-stat-card__label">Відгуків</span>
    </div>
    <div class="admin-stat-card admin-stat-card--accent">
        <span class="admin-stat-card__value"><?= $stats['pending_reviews'] ?></span>
        <span class="admin-stat-card__label">Очікують модерації</span>
    </div>
</div>

<div class="admin-quick-links">
    <a href="<?= BASE_URL ?>/admin/news/create" class="btn btn-primary">+ Додати новину</a>
    <a href="<?= BASE_URL ?>/admin/reviews" class="btn btn-ghost">Переглянути відгуки</a>
</div>