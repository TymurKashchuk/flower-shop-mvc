<?php /** @var array $items */ ?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Товари</h2>
        <p class="admin-page-sub">Керування асортиментом квіткового салону (<?= count($items) ?> поз.)</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/products/create" class="admin-btn admin-btn--primary">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
        </svg>
        Додати товар
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
               placeholder="Пошук за назвою або категорією..."
               data-admin-search="#productsTable">
    </div>

    <div class="admin-filter-group" data-filter-group="#productsTable">
        <button type="button" class="admin-filter-btn active" data-filter="all">Всі товари</button>
        <button type="button" class="admin-filter-btn" data-filter="active">Активні</button>
        <button type="button" class="admin-filter-btn" data-filter="hidden">Приховані</button>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table" id="productsTable">
        <thead>
            <tr>
                <th style="width:70px">Фото</th>
                <th>Назва</th>
                <th>Категорія</th>
                <th>Ціна</th>
                <th>Залишок</th>
                <th>Статус</th>
                <th style="text-align:right">Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="7" class="admin-table-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p>Товарів поки немає. Додайте свій перший букет чи композицію!</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $p): ?>
                <tr data-status="<?= $p['is_active'] ? 'active' : 'hidden' ?>">
                    <td>
                        <?php if (!empty($p['image'])): ?>
                            <img src="<?= htmlspecialchars($p['image']) ?>"
                                 alt="<?= htmlspecialchars($p['name']) ?>"
                                 class="admin-product-cell__thumb"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/80x80?text=Flower'">
                        <?php else: ?>
                            <div class="admin-product-cell__thumb" style="display:flex;align-items:center;justify-content:center;color:var(--admin-text-light)">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="admin-product-cell__name"><?= htmlspecialchars($p['name']) ?></div>
                        <span class="admin-product-cell__sub">ID: #<?= (int)$p['id'] ?></span>
                    </td>
                    <td>
                        <span style="color:var(--admin-text-muted)"><?= htmlspecialchars($p['category_name'] ?? '—') ?></span>
                    </td>
                    <td style="font-weight:700;white-space:nowrap">
                        <?= number_format((float)$p['price'], 2, '.', ' ') ?> ₴
                    </td>
                    <td>
                        <?php if ((int)$p['stock'] === 0): ?>
                            <span class="admin-stock-badge admin-stock-badge--out">Немає (0)</span>
                        <?php elseif ((int)$p['stock'] <= 3): ?>
                            <span class="admin-stock-badge admin-stock-badge--low"><?= (int)$p['stock'] ?> шт.</span>
                        <?php else: ?>
                            <span style="font-weight:500"><?= (int)$p['stock'] ?> шт.</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($p['is_active']): ?>
                            <span class="admin-badge admin-badge--success">Активний</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--neutral">Прихований</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="admin-actions-cell">
                            <a href="<?= BASE_URL ?>/admin/products/<?= (int)$p['id'] ?>/edit"
                               class="admin-btn admin-btn--sm admin-btn--secondary"
                               title="Редагувати товар">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                                Редагувати
                            </a>

                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/products/<?= (int)$p['id'] ?>/delete"
                                  onsubmit="return confirm('Ви впевнені, що хочете видалити товар &quot;<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>&quot;?')">
                                <button type="submit"
                                        class="admin-btn admin-btn--sm admin-btn--danger"
                                        title="Видалити товар">
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