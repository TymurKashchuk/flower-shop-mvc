<?php /** @var array $items */ ?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
        <tr>
            <th>Користувач</th>
            <th>Товар</th>
            <th>Оцінка</th>
            <th>Відгук</th>
            <th>Дата</th>
            <th>Статус</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($items)): ?>
            <tr>
                <td colspan="7" style="text-align:center;color:var(--color-text-muted);padding:var(--space-8)">
                    Відгуків ще немає
                </td>
            </tr>
        <?php endif; ?>
        <?php foreach ($items as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['user_name'] ?? 'Анонім') ?></td>
                <td><?= htmlspecialchars($r['product_name'] ?? '—') ?></td>
                <td>
                    <span class="review-stars--gold" style="font-size:0.95rem">
                        <?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?>
                    </span>
                </td>
                <td style="max-width:240px">
                    <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                        <?= htmlspecialchars($r['text']) ?>
                    </span>
                </td>
                <td style="white-space:nowrap;color:var(--color-text-muted)">
                    <?= date('d.m.Y', strtotime($r['created_at'])) ?>
                </td>
                <td>
                    <?php if ($r['is_active']): ?>
                        <span class="admin-badge admin-badge--success">Опубліковано</span>
                    <?php else: ?>
                        <span class="admin-badge admin-badge--pending">Очікує</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display:flex;gap:var(--space-2)">
                        <?php if (!$r['is_active']): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/approve">
                                <button class="btn btn-primary" style="padding:4px 12px;font-size:var(--text-sm)">
                                    Схвалити
                                </button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/delete"
                              onsubmit="return confirm('Видалити відгук?')">
                            <button class="btn"
                                    style="padding:4px 12px;font-size:var(--text-sm);color:var(--color-error);border-color:var(--color-error)">
                                Видалити
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php if ($r['is_active']): ?>
                <tr>
                    <td colspan="7" style="padding: 0 var(--space-4) var(--space-3);background:var(--color-surface)">
                        <form method="POST"
                              action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/reply"
                              style="display:flex;gap:var(--space-2);align-items:flex-end">
                            <div style="flex:1">
                                <label style="font-size:var(--text-xs);color:var(--color-text-muted);
                                  display:block;margin-bottom:4px">
                                    <?= !empty($r['admin_reply']) ? 'Редагувати відповідь' : 'Відповісти від імені магазину' ?>
                                </label>
                                <textarea name="reply" rows="2"
                                          style="width:100%;border:1px solid var(--color-border);
                                     border-radius:var(--radius-md);padding:6px 10px;
                                     font-size:var(--text-sm);resize:none;outline:none"
                                          placeholder="Ваша відповідь..."><?= htmlspecialchars($r['admin_reply'] ?? '') ?></textarea>
                            </div>
                            <button class="btn btn-primary" style="padding:6px 14px;font-size:var(--text-sm);
                                                       white-space:nowrap;align-self:flex-end">
                                <?= !empty($r['admin_reply']) ? 'Оновити' : 'Надіслати' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>