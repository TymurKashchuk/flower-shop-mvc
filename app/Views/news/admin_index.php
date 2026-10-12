<?php /** @var array $items */ ?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Новини та акції</h2>
        <p class="admin-page-sub">Керування публікаціями, спецпропозиціями та подіями (<?= count($items) ?>)</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/news/create" class="admin-btn admin-btn--primary">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
        </svg>
        Додати новину
    </a>
</div>

<div class="admin-toolbar">
    <div class="admin-search">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="9" r="6"/>
            <path d="M19 19l-4.5-4.5"/>
        </svg>
        <input type="text"
               class="admin-search__input"
               placeholder="Пошук за заголовком новини..."
               data-admin-search="#newsTable">
    </div>

    <div class="admin-filter-group" data-filter-group="#newsTable">
        <button type="button" class="admin-filter-btn active" data-filter="all">Всі новини</button>
        <button type="button" class="admin-filter-btn" data-filter="published">Опубліковані</button>
        <button type="button" class="admin-filter-btn" data-filter="draft">Чернетки</button>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table" id="newsTable">
        <thead>
            <tr>
                <th style="width:70px">Фото</th>
                <th>Заголовок</th>
                <th>Статус</th>
                <th>Дата публікації</th>
                <th style="text-align:right">Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="5" class="admin-table-empty">
                        <p>Новин ще немає. Створіть першу публікацію!</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $item): ?>
                <tr data-status="<?= $item['is_active'] ? 'published' : 'draft' ?>">
                    <td>
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>"
                                 alt="<?= htmlspecialchars($item['title']) ?>"
                                 class="admin-product-cell__thumb"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/80x80?text=News'">
                        <?php else: ?>
                            <div class="admin-product-cell__thumb" style="display:flex;align-items:center;justify-content:center;color:var(--admin-text-light)">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" width="20" height="20">
                                    <path d="M4 4h12M4 8h12M4 12h7"/>
                                    <rect x="2" y="2" width="16" height="16" rx="2"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="font-weight:600;color:var(--admin-text-main)"><?= htmlspecialchars($item['title']) ?></div>
                        <span style="font-size:0.75rem;color:var(--admin-text-muted)">ID: #<?= (int)$item['id'] ?></span>
                    </td>
                    <td>
                        <?php if ($item['is_active']): ?>
                            <span class="admin-badge admin-badge--success">Опубліковано</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--neutral">Чернетка</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--admin-text-muted)">
                        <?= date('d.m.Y', strtotime($item['published_at'] ?? 'now')) ?>
                    </td>
                    <td>
                        <div class="admin-actions-cell">
                            <a href="<?= BASE_URL ?>/admin/news/<?= (int)$item['id'] ?>/edit"
                               class="admin-btn admin-btn--sm admin-btn--secondary"
                               title="Редагувати новину">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                                Редагувати
                            </a>

                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/news/<?= (int)$item['id'] ?>/delete"
                                  onsubmit="return confirm('Видалити новину &quot;<?= htmlspecialchars($item['title'], ENT_QUOTES) ?>&quot;?')">
                                <button type="submit"
                                        class="admin-btn admin-btn--sm admin-btn--danger"
                                        title="Видалити новину">
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