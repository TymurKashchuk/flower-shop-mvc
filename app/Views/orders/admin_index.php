<?php /** @var array $items */ ?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Замовлення</h2>
        <p class="admin-page-sub">Керування замовленнями, зміна статусів та обробка заявок (<?= count($items) ?>)</p>
    </div>
</div>

<div class="admin-toolbar">
    <div class="admin-search">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="9" r="6"/>
            <path d="M19 19l-4.5-4.5"/>
        </svg>
        <input type="text"
               class="admin-search__input"
               placeholder="Пошук за номером, клієнтом чи телефоном..."
               data-admin-search="#ordersTable">
    </div>

    <div class="admin-filter-group" data-filter-group="#ordersTable">
        <button type="button" class="admin-filter-btn active" data-filter="all">Всі замовлення</button>
        <button type="button" class="admin-filter-btn" data-filter="new">Нові</button>
        <button type="button" class="admin-filter-btn" data-filter="processing">В обробці</button>
        <button type="button" class="admin-filter-btn" data-filter="done">Виконані</button>
        <button type="button" class="admin-filter-btn" data-filter="cancelled">Скасовані</button>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table" id="ordersTable">
        <thead>
            <tr>
                <th style="width:70px">№</th>
                <th>Клієнт</th>
                <th>Телефон</th>
                <th>Доставка</th>
                <th>Сума</th>
                <th>Статус</th>
                <th>Дата</th>
                <th style="text-align:right">Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="8" class="admin-table-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <path d="M7 8h10M7 12h10M7 16h6"/>
                        </svg>
                        <p>Замовлень ще немає.</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $order): ?>
                <tr data-status="<?= htmlspecialchars($order['status']) ?>">
                    <td style="font-weight:700">#<?= (int)$order['id'] ?></td>
                    <td>
                        <div style="font-weight:600;color:var(--admin-text-main)"><?= htmlspecialchars($order['name']) ?></div>
                        <?php if (!empty($order['email'])): ?>
                            <div style="font-size:0.75rem;color:var(--admin-text-muted)"><?= htmlspecialchars($order['email']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="tel:<?= htmlspecialchars($order['phone']) ?>" style="color:inherit;text-decoration:none;font-weight:500">
                            <?= htmlspecialchars($order['phone']) ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($order['delivery_type'] === 'pickup'): ?>
                            <span class="admin-badge admin-badge--neutral" title="Самовивіз з магазину">
                                Самовивіз
                            </span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--info" title="Кур'єрська доставка">
                                Курʼєр
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;font-weight:700">
                        <?= number_format((float)$order['total'], 2, '.', ' ') ?> ₴
                    </td>
                    <td>
                        <?php if ($order['status'] === 'new'): ?>
                            <span class="admin-badge admin-badge--warning">Нове</span>
                        <?php elseif ($order['status'] === 'processing'): ?>
                            <span class="admin-badge admin-badge--info">В обробці</span>
                        <?php elseif ($order['status'] === 'done'): ?>
                            <span class="admin-badge admin-badge--success">Виконано</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--danger">Скасовано</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--admin-text-muted)">
                        <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                    </td>
                    <td>
                        <div class="admin-actions-cell">
                            <a href="<?= BASE_URL ?>/admin/orders/<?= (int)$order['id'] ?>"
                               class="admin-btn admin-btn--sm admin-btn--secondary">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path d="M2.458 10C3.732 6.943 6.632 4.5 10 4.5s6.268 2.443 7.542 5.5c-1.274 3.057-4.174 5.5-7.542 5.5S3.732 13.057 2.458 10z"/>
                                </svg>
                                Деталі
                            </a>

                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/orders/<?= (int)$order['id'] ?>/delete"
                                  onsubmit="return confirm('Видалити замовлення #<?= (int)$order['id'] ?>?')">
                                <button type="submit"
                                        class="admin-btn admin-btn--sm admin-btn--danger"
                                        title="Видалити замовлення">
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <path d="M6 7h8m-7 0v8a1 1 0 001 1h4a1 1 0 001-1V7m-5-3h2a1 1 0 011 1v2H7V5a1 1 0 011-1z"/>
                                    </svg>
                                    Видалити
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>