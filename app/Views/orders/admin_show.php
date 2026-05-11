<?php /** @var array $order */ ?>
<?php /** @var array $items */ ?>

<div class="admin-section">
    <div class="admin-order-head">
        <div>
            <a href="<?= BASE_URL ?>/admin/orders" class="admin-back-link">&larr; До списку замовлень</a>
            <h2 class="admin-section__title">Замовлення #<?= (int)$order['id'] ?></h2>
            <p class="admin-section__sub">
                Створено <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
            </p>
        </div>

        <form method="POST"
              action="<?= BASE_URL ?>/admin/orders/<?= (int)$order['id'] ?>/status"
              class="admin-order-status-form">
            <select name="status" class="form-input admin-order-status-select">
                <option value="new" <?= $order['status'] === 'new' ? 'selected' : '' ?>>Нове</option>
                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>В обробці</option>
                <option value="done" <?= $order['status'] === 'done' ? 'selected' : '' ?>>Виконано</option>
                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Скасовано</option>
            </select>
            <button class="btn btn-primary">Оновити статус</button>
        </form>
    </div>

    <div class="admin-order-grid">
        <div class="admin-info-card">
            <div class="admin-info-card__head">
                <h3>Клієнт</h3>
                <?php if (!empty($order['email'])): ?>
                    <span class="admin-info-card__meta"><?= htmlspecialchars($order['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="admin-info-list">
                <div class="admin-info-list__row">
                    <span>Ім’я</span>
                    <strong><?= htmlspecialchars($order['name']) ?></strong>
                </div>
                <div class="admin-info-list__row">
                    <span>Телефон</span>
                    <strong><?= htmlspecialchars($order['phone']) ?></strong>
                </div>
                <div class="admin-info-list__row">
                    <span>Доставка</span>
                    <strong><?= $order['delivery_type'] === 'pickup' ? 'Самовивіз' : 'Курʼєр' ?></strong>
                </div>
                <div class="admin-info-list__row">
                    <span>Адреса</span>
                    <strong><?= htmlspecialchars($order['address'] ?: '—') ?></strong>
                </div>
            </div>
        </div>

        <div class="admin-info-card">
            <div class="admin-info-card__head">
                <h3>Підсумок</h3>
                <?php if ($order['status'] === 'new'): ?>
                    <span class="admin-badge admin-badge--pending">Нове</span>
                <?php elseif ($order['status'] === 'processing'): ?>
                    <span class="admin-badge admin-badge--info">В обробці</span>
                <?php elseif ($order['status'] === 'done'): ?>
                    <span class="admin-badge admin-badge--success">Виконано</span>
                <?php else: ?>
                    <span class="admin-badge admin-badge--error">Скасовано</span>
                <?php endif; ?>
            </div>

            <div class="admin-order-total-card">
                <span>Сума замовлення</span>
                <strong><?= number_format((float)$order['total'], 2, '.', ' ') ?> грн</strong>
            </div>
        </div>
    </div>

    <div class="admin-table-wrap admin-order-items">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Товар</th>
                <th>Кількість</th>
                <th>Ціна</th>
                <th>Разом</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="4" class="admin-table__empty">У замовленні немає товарів</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <a href="<?= BASE_URL ?>/catalog/<?= htmlspecialchars($item['product_slug']) ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="admin-table__link">
                            <?= htmlspecialchars($item['product_name']) ?>
                        </a>
                    </td>
                    <td><?= (int)$item['qty'] ?></td>
                    <td><?= number_format((float)$item['price'], 2, '.', ' ') ?> грн</td>
                    <td class="admin-order-total">
                        <?= number_format($item['qty'] * $item['price'], 2, '.', ' ') ?> грн
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>