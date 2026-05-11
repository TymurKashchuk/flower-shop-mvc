<?php /** @var array $items */ ?>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-8)">
        <div>
            <h2 style="font-family:var(--font-display);font-size:var(--text-xl);font-weight:600;color:var(--color-text);margin-bottom:4px;">
                Замовлення
            </h2>
            <p style="font-size:var(--text-sm);color:var(--color-text-muted);">
                Перегляд, оновлення статусу та видалення замовлень.
            </p>
        </div>
    </div>

<?php if (empty($items)): ?>
    <p style="color:var(--color-text-muted)">Замовлень ще немає.</p>
<?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Клієнт</th>
                <th>Телефон</th>
                <th>Доставка</th>
                <th>Сума</th>
                <th>Статус</th>
                <th>Дата</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $order): ?>
                <tr>
                    <td>#<?= (int)$order['id'] ?></td>
                    <td><?= htmlspecialchars($order['name']) ?></td>
                    <td><?= htmlspecialchars($order['phone']) ?></td>
                    <td><?= $order['delivery_type'] === 'pickup' ? 'Самовивіз' : 'Курʼєр' ?></td>
                    <td style="white-space:nowrap;font-weight:600;">
                        <?= number_format((float)$order['total'], 2, '.', ' ') ?> грн
                    </td>
                    <td>
                        <?php if ($order['status'] === 'new'): ?>
                            <span class="admin-badge admin-badge--pending">Нове</span>
                        <?php elseif ($order['status'] === 'processing'): ?>
                            <span class="admin-badge">В обробці</span>
                        <?php elseif ($order['status'] === 'done'): ?>
                            <span class="admin-badge admin-badge--success">Виконано</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--error">Скасовано</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;color:var(--color-text-muted)">
                        <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:var(--space-2);justify-content:flex-end;flex-wrap:wrap;">
                            <a href="<?= BASE_URL ?>/admin/orders/<?= (int)$order['id'] ?>"
                               class="btn btn-ghost"
                               style="padding:4px 12px;font-size:var(--text-sm)">
                                Деталі
                            </a>

                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/orders/<?= (int)$order['id'] ?>/delete"
                                  onsubmit="return confirm('Видалити замовлення #<?= (int)$order['id'] ?>?')">
                                <button type="submit"
                                        class="btn"
                                        style="padding:4px 12px;font-size:var(--text-sm);color:var(--color-error);border-color:var(--color-error)">
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
<?php endif; ?>