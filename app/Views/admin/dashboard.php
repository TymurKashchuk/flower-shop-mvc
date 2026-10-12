<?php
/** @var array $stats */
/** @var array $recentOrders */
/** @var array $recentReviews */
?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Огляд магазину</h2>
        <p class="admin-page-sub">Ключові показники та останні події станом на <?= date('d.m.Y') ?></p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="<?= BASE_URL ?>/admin/products/create" class="admin-btn admin-btn--primary">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Додати товар
        </a>
        <a href="<?= BASE_URL ?>/admin/news/create" class="admin-btn admin-btn--secondary">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                <path d="M10 4v12M4 10h12" stroke-linecap="round"/>
            </svg>
            Додати новину
        </a>
    </div>
</div>

<!-- Attention banner for new orders or reviews -->
<?php if ($stats['new_orders'] > 0 || $stats['pending_reviews'] > 0): ?>
    <div style="background:var(--admin-warning-bg);border:1px solid var(--admin-warning-border);border-radius:var(--admin-radius);padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:12px">
            <svg viewBox="0 0 20 20" fill="currentColor" style="width:22px;height:22px;color:var(--admin-warning);flex-shrink:0;">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span style="font-size:0.875rem;font-weight:600;color:#78350f;">
                Потребують уваги: 
                <?php if ($stats['new_orders'] > 0): ?>
                    <a href="<?= BASE_URL ?>/admin/orders" style="color:#b45309;text-decoration:underline;"><?= $stats['new_orders'] ?> нов. замовлень</a>
                <?php endif; ?>
                <?= ($stats['new_orders'] > 0 && $stats['pending_reviews'] > 0) ? 'та ' : '' ?>
                <?php if ($stats['pending_reviews'] > 0): ?>
                    <a href="<?= BASE_URL ?>/admin/reviews" style="color:#b45309;text-decoration:underline;"><?= $stats['pending_reviews'] ?> відгуків на модерації</a>
                <?php endif; ?>
            </span>
        </div>
        <a href="<?= BASE_URL ?>/admin/orders" class="admin-btn admin-btn--sm admin-btn--secondary">
            Перейти до замовлень
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="14" height="14">
                <path d="M4 10h12m0 0l-4-4m4 4l-4 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
<?php endif; ?>

<!-- Stat cards -->
<div class="admin-stats-grid">
    <!-- Revenue -->
    <div class="admin-stat-card">
        <div class="admin-stat-card__content">
            <span class="admin-stat-card__label">Виручка (виконано)</span>
            <span class="admin-stat-card__value"><?= number_format($stats['revenue'], 0, '.', ' ') ?> ₴</span>
            <span class="admin-stat-card__meta">За всі виконані замовлення</span>
        </div>
        <div class="admin-stat-card__icon-wrap admin-stat-card__icon-wrap--success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
            </svg>
        </div>
    </div>

    <!-- Orders -->
    <div class="admin-stat-card">
        <div class="admin-stat-card__content">
            <span class="admin-stat-card__label">Замовлення</span>
            <span class="admin-stat-card__value"><?= $stats['orders'] ?></span>
            <span class="admin-stat-card__meta" style="color:var(--admin-warning);font-weight:600">
                <?= $stats['new_orders'] > 0 ? "+{$stats['new_orders']} нових" : "Немає нових" ?>
            </span>
        </div>
        <div class="admin-stat-card__icon-wrap admin-stat-card__icon-wrap--warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>
    </div>

    <!-- Products -->
    <div class="admin-stat-card">
        <div class="admin-stat-card__content">
            <span class="admin-stat-card__label">Товари в каталозі</span>
            <span class="admin-stat-card__value"><?= $stats['products'] ?></span>
            <span class="admin-stat-card__meta" style="display:flex;align-items:center;gap:4px">
                <?php if ($stats['low_stock'] > 0): ?>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14" style="color:var(--admin-warning);flex-shrink:0">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= $stats['low_stock'] ?> закінчуються</span>
                <?php else: ?>
                    <span>Всі в наявності</span>
                <?php endif; ?>
            </span>
        </div>
        <div class="admin-stat-card__icon-wrap admin-stat-card__icon-wrap--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
    </div>

    <!-- Reviews -->
    <div class="admin-stat-card">
        <div class="admin-stat-card__content">
            <span class="admin-stat-card__label">Відгуки</span>
            <span class="admin-stat-card__value"><?= $stats['reviews'] ?></span>
            <span class="admin-stat-card__meta">
                <?= $stats['pending_reviews'] > 0 ? "{$stats['pending_reviews']} очікують схвалення" : "Всі промодеровано" ?>
            </span>
        </div>
        <div class="admin-stat-card__icon-wrap admin-stat-card__icon-wrap--info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
            </svg>
        </div>
    </div>
</div>

<!-- Main 2-column layout for dashboard -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start">
    
    <!-- Left: Recent Orders -->
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="18" height="18">
                    <rect x="3" y="4" width="14" height="12" rx="2"/>
                    <path d="M6 8h8M6 11h8M6 14h5"/>
                </svg>
                Останні замовлення
            </h3>
            <a href="<?= BASE_URL ?>/admin/orders" class="admin-btn admin-btn--sm admin-btn--ghost">
                Всі замовлення
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="14" height="14">
                    <path d="M4 10h12m0 0l-4-4m4 4l-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
        
        <div class="admin-table-wrap" style="border:none;border-radius:0;box-shadow:none">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Клієнт</th>
                        <th>Сума</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th style="text-align:right">Дія</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="6" class="admin-table-empty">
                                <p>Замовлень ще немає</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $o): ?>
                            <tr>
                                <td style="font-weight:700">#<?= (int)$o['id'] ?></td>
                                <td>
                                    <div style="font-weight:600"><?= htmlspecialchars($o['name']) ?></div>
                                    <div style="font-size:0.75rem;color:var(--admin-text-muted)"><?= htmlspecialchars($o['phone']) ?></div>
                                </td>
                                <td style="font-weight:700;white-space:nowrap">
                                    <?= number_format((float)$o['total'], 2, '.', ' ') ?> ₴
                                </td>
                                <td>
                                    <?php if ($o['status'] === 'new'): ?>
                                        <span class="admin-badge admin-badge--warning">Нове</span>
                                    <?php elseif ($o['status'] === 'processing'): ?>
                                        <span class="admin-badge admin-badge--info">В обробці</span>
                                    <?php elseif ($o['status'] === 'done'): ?>
                                        <span class="admin-badge admin-badge--success">Виконано</span>
                                    <?php else: ?>
                                        <span class="admin-badge admin-badge--danger">Скасовано</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size:0.8rem;color:var(--admin-text-muted);white-space:nowrap">
                                    <?= date('d.m H:i', strtotime($o['created_at'])) ?>
                                </td>
                                <td style="text-align:right">
                                    <a href="<?= BASE_URL ?>/admin/orders/<?= (int)$o['id'] ?>" class="admin-btn admin-btn--sm admin-btn--secondary">
                                        Деталі
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Quick actions & Recent Reviews -->
    <div style="display:flex;flex-direction:column;gap:24px">
        <!-- Quick actions -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="18" height="18">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Швидкі дії
                </h3>
            </div>
            <div class="admin-card__body" style="display:flex;flex-direction:column;gap:8px">
                <a href="<?= BASE_URL ?>/admin/products/create" class="admin-btn admin-btn--secondary" style="justify-content:flex-start">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                        <circle cx="10" cy="10" r="8"/>
                        <line x1="10" y1="6" x2="10" y2="14"/>
                        <line x1="6" y1="10" x2="14" y2="10"/>
                    </svg>
                    Додати новий товар
                </a>
                <a href="<?= BASE_URL ?>/admin/news/create" class="admin-btn admin-btn--secondary" style="justify-content:flex-start">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M4 4h12M4 8h12M4 12h7"/>
                        <rect x="2" y="2" width="16" height="16" rx="2"/>
                    </svg>
                    Створити новину чи акцію
                </a>
                <a href="<?= BASE_URL ?>/admin/reviews" class="admin-btn admin-btn--secondary" style="justify-content:flex-start">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M10 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4L10 14.4l-4.8 2.5.9-5.4L2.2 7.7l5.4-.8z"/>
                    </svg>
                    Модерація відгуків
                </a>
                <a href="<?= BASE_URL ?>/admin/users" class="admin-btn admin-btn--secondary" style="justify-content:flex-start">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path d="M18 17a6 6 0 10-12 0"/>
                    </svg>
                    Керування користувачами
                </a>
            </div>
        </div>

        <!-- Recent Reviews Widget -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="color:var(--admin-warning)">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Останні відгуки
                </h3>
                <a href="<?= BASE_URL ?>/admin/reviews" class="admin-btn admin-btn--sm admin-btn--ghost">
                    Всі
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="14" height="14">
                        <path d="M4 10h12m0 0l-4-4m4 4l-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <div class="admin-card__body" style="padding:12px 16px;display:flex;flex-direction:column;gap:12px">
                <?php if (empty($recentReviews)): ?>
                    <p style="font-size:0.85rem;color:var(--admin-text-muted);text-align:center;padding:12px 0">Відгуків поки немає</p>
                <?php else: ?>
                    <?php foreach ($recentReviews as $rev): ?>
                        <div style="border-bottom:1px solid var(--admin-border-subtle);padding-bottom:10px;display:flex;flex-direction:column;gap:4px">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
                                <span style="font-size:0.85rem;font-weight:600"><?= htmlspecialchars($rev['user_name'] ?? 'Анонім') ?></span>
                                <span style="display:inline-flex;gap:2px;color:#f59e0b" aria-label="<?= (int)$rev['rating'] ?> з 5">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <svg viewBox="0 0 20 20" fill="<?= $s <= (int)$rev['rating'] ? 'currentColor' : '#e2e8f0' ?>" width="13" height="13">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <div style="font-size:0.75rem;color:var(--admin-text-muted)">
                                До товару: <strong><?= htmlspecialchars($rev['product_name'] ?? '—') ?></strong>
                            </div>
                            <p style="font-size:0.8rem;color:var(--admin-text-main);margin:2px 0 0;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                <?= htmlspecialchars($rev['text']) ?>
                            </p>
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:2px">
                                <?php if ($rev['is_active']): ?>
                                    <span class="admin-badge admin-badge--success" style="font-size:0.68rem;padding:1px 6px">Опубліковано</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge--warning" style="font-size:0.68rem;padding:1px 6px">Очікує модерації</span>
                                <?php endif; ?>
                                <span style="font-size:0.72rem;color:var(--admin-text-light)"><?= date('d.m.Y', strtotime($rev['created_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>