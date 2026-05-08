<?php /** @var array $items */ ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-6)">
    <span style="color:var(--color-text-muted)"><?= count($items) ?> товарів</span>
    <a href="<?= BASE_URL ?>/admin/products/create" class="btn btn-primary">+ Додати товар</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
        <tr>
            <th>Назва</th>
            <th>Категорія</th>
            <th>Ціна</th>
            <th>Залишок</th>
            <th>Статус</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($items)): ?>
            <tr>
                <td colspan="6" style="text-align:center;padding:var(--space-8);color:var(--color-text-muted)">Товарів
                    немає
                </td>
            </tr>
        <?php endif; ?>
        <?php foreach ($items as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td style="color:var(--color-text-muted)"><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                <td><?= number_format($p['price'], 2) ?> грн</td>
                <td><?= $p['stock'] ?></td>
                <td>
                    <?php if ($p['is_active']): ?>
                        <span class="admin-badge admin-badge--success">Активний</span>
                    <?php else: ?>
                        <span class="admin-badge">Прихований</span>
                    <?php endif; ?>
                </td>
                <td style="display:flex;gap:var(--space-2)">
                    <a href="<?= BASE_URL ?>/admin/products/<?= $p['id'] ?>/edit"
                       class="btn btn-primary" style="padding:4px 12px;font-size:var(--text-sm)">
                        Редагувати
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>/admin/products/<?= $p['id'] ?>/delete"
                          onsubmit="return confirm('Видалити товар?')">
                        <button class="btn"
                                style="padding:4px 12px;font-size:var(--text-sm);color:var(--color-error);border-color:var(--color-error)">
                            Видалити
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>