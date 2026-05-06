<?php /** @var array $items */ /** @var int $page */ /** @var int $pages */ ?>

    <div class="catalog-header">
        <h1 class="catalog-header__title">Новини</h1>
    </div>

<?php if (empty($items)): ?>
    <div class="empty-state">
        <div class="empty-state__icon">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM3 9h18"
                      stroke="currentColor" stroke-width="1.5"/>
            </svg>
        </div>
        <h2 class="empty-state__title">Новин поки немає</h2>
        <p class="empty-state__desc">Заходьте пізніше</p>
    </div>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($items as $item): ?>
            <a href="<?= BASE_URL ?>/news/<?= htmlspecialchars($item['slug']) ?>" class="product-card">
                <div class="product-card__body">
                    <p class="product-card__category">
                        <?= $item['published_at']
                                ? date('d.m.Y', strtotime($item['published_at']))
                                : '' ?>
                    </p>
                    <h2 class="product-card__name">
                        <?= htmlspecialchars($item['title']) ?>
                    </h2>
                    <p style="font-size:var(--text-sm);color:var(--color-text-muted);margin-top:var(--space-2)">
                        <?= htmlspecialchars(mb_substr($item['content'] ?? '', 0, 120)) ?>...
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Сторінки">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?page=<?= $i ?>"
                   class="pagination__btn <?= $i === $page ? 'pagination__btn--active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>