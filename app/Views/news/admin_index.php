<?php /** @var array $items */ ?>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-8)">
        <a href="<?= BASE_URL ?>/admin/news/create" class="btn btn-primary">+ Додати</a>
    </div>

<?php if (empty($items)): ?>
    <p style="color:var(--color-text-muted)">Новин ще немає.</p>
<?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Заголовок</th>
                <th>Статус</th>
                <th>Дата</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td>
                        <?php if ($item['is_active']): ?>
                            <span class="admin-badge admin-badge--success">Опубліковано</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--pending">Чернетка</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;color:var(--color-text-muted)">
                        <?= date('d.m.Y', strtotime($item['published_at'])) ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:var(--space-2)">
                            <a href="<?= BASE_URL ?>/admin/news/<?= $item['id'] ?>/edit"
                               class="btn btn-ghost" style="padding:4px 12px;font-size:var(--text-sm)">
                                Редагувати
                            </a>
                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/news/<?= $item['id'] ?>/delete"
                                  onsubmit="return confirm('Видалити новину?')">
                                <button class="btn" style="padding:4px 12px;font-size:var(--text-sm);
                                    color:var(--color-error);border-color:var(--color-error)">
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