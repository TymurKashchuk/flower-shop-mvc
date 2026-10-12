<?php
/** @var array $order */
/** @var array $items */
?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <div style="display:flex;align-items:center;gap:10px">
            <h2 class="admin-page-title">Замовлення #<?= (int)$order['id'] ?></h2>
            <?php if ($order['status'] === 'new'): ?>
                <span class="admin-badge admin-badge--warning">Нове</span>
            <?php elseif ($order['status'] === 'processing'): ?>
                <span class="admin-badge admin-badge--info">В обробці</span>
            <?php elseif ($order['status'] === 'done'): ?>
                <span class="admin-badge admin-badge--success">Виконано</span>
            <?php else: ?>
                <span class="admin-badge admin-badge--danger">Скасовано</span>
            <?php endif; ?>
        </div>
        <p class="admin-page-sub">
            Оформлено <?= date('d.m.Y о H:i', strtotime($order['created_at'])) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>/admin/orders" class="admin-btn admin-btn--secondary">
        &larr; До всіх замовлень
    </a>
</div>

<div class="admin-order-overview-grid">
    
    <!-- Left: Order items & Customer details -->
    <div style="display:flex;flex-direction:column;gap:24px">
        
        <!-- Ordered items table -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="18" height="18">
                        <path d="M16 11V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5m12 0l-2 5H6l-2-5m12 0H4"/>
                    </svg>
                    Склад замовлення (<?= count($items) ?> поз.)
                </h3>
            </div>
            
            <div class="admin-table-wrap" style="border:none;border-radius:0;box-shadow:none">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th style="text-align:center">Кількість</th>
                            <th style="text-align:right">Ціна</th>
                            <th style="text-align:right">Разом</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="4" class="admin-table-empty">У замовленні немає товарів</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <?php if (!empty($item['product_image'])): ?>
                                            <img src="<?= htmlspecialchars($item['product_image']) ?>"
                                                 alt=""
                                                 class="admin-product-cell__thumb"
                                                 onerror="this.src='https://placehold.co/80x80?text=Item'">
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?= BASE_URL ?>/catalog/<?= htmlspecialchars($item['product_slug'] ?? '') ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               style="font-weight:600;color:var(--admin-text-main);text-decoration:none">
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </a>
                                            <?php if (!empty($item['product_category'])): ?>
                                                <div style="font-size:0.75rem;color:var(--admin-text-muted)"><?= htmlspecialchars($item['product_category']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:center;font-weight:600"><?= (int)$item['qty'] ?> шт.</td>
                                <td style="text-align:right;white-space:nowrap;color:var(--admin-text-muted)">
                                    <?= number_format((float)$item['price'], 2, '.', ' ') ?> ₴
                                </td>
                                <td style="text-align:right;white-space:nowrap;font-weight:700">
                                    <?= number_format($item['qty'] * $item['price'], 2, '.', ' ') ?> ₴
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Total sum footer -->
            <div style="padding:16px 20px;background:#fafbfc;border-top:1px solid var(--admin-border-subtle);display:flex;justify-content:flex-end;align-items:baseline;gap:12px">
                <span style="font-size:0.875rem;color:var(--admin-text-muted)">Загальна сума до сплати:</span>
                <span style="font-size:1.4rem;font-weight:800;color:var(--admin-primary)">
                    <?= number_format((float)$order['total'], 2, '.', ' ') ?> ₴
                </span>
            </div>
        </div>

        <!-- Customer & Delivery card -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="18" height="18">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                    Інформація про клієнта та доставку
                </h3>
            </div>
            <div class="admin-card__body">
                <div class="admin-info-item">
                    <span class="admin-info-item__label">Ім'я одержувача</span>
                    <span class="admin-info-item__value"><?= htmlspecialchars($order['name']) ?></span>
                </div>
                <div class="admin-info-item">
                    <span class="admin-info-item__label">Контактний телефон</span>
                    <span class="admin-info-item__value">
                        <a href="tel:<?= htmlspecialchars($order['phone']) ?>">
                            <?= htmlspecialchars($order['phone']) ?>
                        </a>
                    </span>
                </div>
                <?php if (!empty($order['email'])): ?>
                    <div class="admin-info-item">
                        <span class="admin-info-item__label">Електронна пошта</span>
                        <span class="admin-info-item__value">
                            <a href="mailto:<?= htmlspecialchars($order['email']) ?>">
                                <?= htmlspecialchars($order['email']) ?>
                            </a>
                        </span>
                    </div>
                <?php endif; ?>
                <div class="admin-info-item">
                    <span class="admin-info-item__label">Спосіб отримання</span>
                    <span class="admin-info-item__value">
                        <?php if ($order['delivery_type'] === 'pickup'): ?>
                            <span class="admin-badge admin-badge--neutral">Самовивіз з салону</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--info">Кур'єрська доставка</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="admin-info-item">
                    <span class="admin-info-item__label">Адреса доставки</span>
                    <span class="admin-info-item__value">
                        <?= !empty($order['address']) ? htmlspecialchars($order['address']) : '<span style="color:var(--admin-text-light)">Самовивіз / не вказано</span>' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Status management & Actions -->
    <div style="display:flex;flex-direction:column;gap:24px">
        
        <!-- Status change card -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">Статус замовлення</h3>
            </div>
            <div class="admin-card__body">
                <form method="POST" action="<?= BASE_URL ?>/admin/orders/<?= (int)$order['id'] ?>/status">
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="orderStatusSelect">Поточний стан</label>
                        <select name="status" id="orderStatusSelect" class="admin-select" style="margin-bottom:12px">
                            <option value="new" <?= $order['status'] === 'new' ? 'selected' : '' ?>>🟡 Нове замовлення</option>
                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>🔵 В обробці (збирається)</option>
                            <option value="done" <?= $order['status'] === 'done' ? 'selected' : '' ?>>🟢 Виконано (доставлено)</option>
                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>🔴 Скасовано</option>
                        </select>
                    </div>

                    <button type="submit" class="admin-btn admin-btn--primary" style="width:100%">
                        Зберегти новий статус
                    </button>
                </form>
            </div>
        </div>

        <!-- Danger zone: Delete order -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title" style="color:var(--admin-danger)">Небезпечна зона</h3>
            </div>
            <div class="admin-card__body">
                <p style="font-size:0.8rem;color:var(--admin-text-muted);margin-bottom:14px;line-height:1.4">
                    Видалення замовлення безповоротно видалить його історію та склад позицій з бази даних.
                </p>
                <form method="POST"
                      action="<?= BASE_URL ?>/admin/orders/<?= (int)$order['id'] ?>/delete"
                      onsubmit="return confirm('Видалити замовлення #<?= (int)$order['id'] ?>? Це незворотно.')">
                    <button type="submit" class="admin-btn admin-btn--danger" style="width:100%">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M6 7h8m-7 0v8a1 1 0 001 1h4a1 1 0 001-1V7m-5-3h2a1 1 0 011 1v2H7V5a1 1 0 011-1z"/>
                        </svg>
                        Видалити замовлення
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>