<?php /** @var array $items */ ?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Відгуки клієнтів</h2>
        <p class="admin-page-sub">Модерація відгуків на товари та відповіді від імені салону (<?= count($items) ?>)</p>
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
               placeholder="Пошук за клієнтом, товаром чи текстом..."
               data-admin-search="#reviewsTable">
    </div>

    <div class="admin-filter-group" data-filter-group="#reviewsTable">
        <button type="button" class="admin-filter-btn active" data-filter="all">Всі відгуки</button>
        <button type="button" class="admin-filter-btn" data-filter="pending">Очікують модерації</button>
        <button type="button" class="admin-filter-btn" data-filter="active">Опубліковані</button>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table" id="reviewsTable">
        <thead>
            <tr>
                <th>Клієнт</th>
                <th>Товар</th>
                <th>Оцінка</th>
                <th style="min-width:280px">Відгук та відповідь</th>
                <th>Дата</th>
                <th>Статус</th>
                <th style="text-align:right">Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="7" class="admin-table-empty">
                        <p>Відгуків ще немає</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $r): ?>
                <tr data-status="<?= $r['is_active'] ? 'active' : 'pending' ?>">
                    <td>
                        <div style="font-weight:600"><?= htmlspecialchars($r['user_name'] ?? 'Анонім') ?></div>
                        <span style="font-size:0.75rem;color:var(--admin-text-muted)">ID: #<?= (int)$r['id'] ?></span>
                    </td>
                    <td>
                        <strong style="color:var(--admin-text-main)"><?= htmlspecialchars($r['product_name'] ?? '—') ?></strong>
                    </td>
                    <td>
                        <span style="color:#f59e0b;font-size:0.95rem;white-space:nowrap">
                            <?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?>
                        </span>
                    </td>
                    <td>
                        <div style="font-size:0.875rem;line-height:1.5;color:var(--admin-text-main);margin-bottom:8px">
                            <?= nl2br(htmlspecialchars($r['text'])) ?>
                        </div>

                        <!-- Store reply block -->
                        <?php if ($r['is_active']): ?>
                            <details style="background:var(--admin-surface-subtle);border:1px solid var(--admin-border);border-radius:var(--admin-radius);padding:8px 12px;margin-top:6px"
                                     <?= !empty($r['admin_reply']) ? 'open' : '' ?>>
                                <summary style="font-size:0.75rem;font-weight:600;color:var(--admin-primary);cursor:pointer">
                                    <?= !empty($r['admin_reply']) ? 'Відповідь салону (редагувати)' : '+ Відповісти від імені магазину' ?>
                                </summary>
                                <form method="POST"
                                      action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/reply"
                                      style="display:flex;gap:8px;align-items:flex-end;margin-top:8px">
                                    <div style="flex:1">
                                        <textarea name="reply"
                                                  class="admin-textarea"
                                                  rows="2"
                                                  style="font-size:0.825rem;padding:6px 10px;resize:vertical"
                                                  placeholder="Текст відповіді магазину..."><?= htmlspecialchars($r['admin_reply'] ?? '') ?></textarea>
                                    </div>
                                    <button class="admin-btn admin-btn--sm admin-btn--primary" style="align-self:flex-end">
                                        <?= !empty($r['admin_reply']) ? 'Оновити' : 'Надіслати' ?>
                                    </button>
                                </form>
                            </details>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--admin-text-muted)">
                        <?= date('d.m.Y', strtotime($r['created_at'])) ?>
                    </td>
                    <td>
                        <?php if ($r['is_active']): ?>
                            <span class="admin-badge admin-badge--success">Опубліковано</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--warning">Очікує</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="admin-actions-cell">
                            <?php if (!$r['is_active']): ?>
                                <form method="POST" action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/approve">
                                    <button class="admin-btn admin-btn--sm admin-btn--success" title="Схвалити і опублікувати">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Схвалити
                                    </button>
                                </form>
                            <?php endif; ?>

                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/delete"
                                  onsubmit="return confirm('Видалити цей відгук?')">
                                <button class="admin-btn admin-btn--sm admin-btn--danger" title="Видалити">
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