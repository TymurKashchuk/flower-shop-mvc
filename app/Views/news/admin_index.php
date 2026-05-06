<?php /** @var array $items */ ?>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-8)">
        <h1 style="font-family:var(--font-display);font-size:var(--text-xl)">Новини</h1>
        <a href="<?= BASE_URL ?>/admin/news/create" class="btn btn-primary">+ Додати</a>
    </div>

<?php if (empty($items)): ?>
    <p style="color:var(--color-text-muted)">Новин ще немає.</p>
<?php else: ?>
    <table style="width:100%;border-collapse:collapse;font-size:var(--text-sm)">
        <thead>
        <tr style="border-bottom:1px solid var(--color-border)">
            <th style="text-align:left;padding:var(--space-3)">Заголовок</th>
            <th style="text-align:left;padding:var(--space-3)">Статус</th>
            <th style="text-align:left;padding:var(--space-3)">Дата</th>
            <th style="padding:var(--space-3)"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr style="border-bottom:1px solid var(--color-border)">
                <td style="padding:var(--space-3)"><?= htmlspecialchars($item['title']) ?></td>
                <td style="padding:var(--space-3)">
                        <span style="color:<?= $item['is_published'] ? 'var(--color-text-success)' : 'var(--color-text-muted)' ?>">
                            <?= $item['is_active'] ? 'Опубліковано' : 'Чернетка' ?>
                        </span>
                </td>
                <td style="padding:var(--space-3)">
                    <?= date('d.m.Y', strtotime($item['published_at'] ?? 'now')) ?>
                </td>
                <td style="padding:var(--space-3);display:flex;gap:var(--space-2)">
                    <a href="<?= BASE_URL ?>/admin/news/<?= $item['id'] ?>/edit"
                       class="btn btn-ghost" style="padding:4px 12px">Ред.</a>
                    <form action="<?= BASE_URL ?>/admin/news/<?= $item['id'] ?>/delete"
                          method="POST"
                          onsubmit="return confirm('Видалити?')">
                        <button type="submit" class="btn"
                                style="padding:4px 12px;color:var(--color-error);
                                           border-color:var(--color-error)">
                            Видалити
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>