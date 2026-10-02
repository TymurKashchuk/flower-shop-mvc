<div class="catalog-header">
    <h1 class="catalog-header__title">Каталог квітів</h1>

    <!-- Фільтр по категоріях -->
    <?php if (!empty($categories)): ?>
        <div class="catalog-filters">
            <a href="<?= BASE_URL ?>/catalog"
               class="filter-chip <?= empty($categorySlug) ? 'filter-chip--active' : '' ?>">
                Всі
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>/catalog?category=<?= htmlspecialchars($cat['slug']) ?>"
                   class="filter-chip <?= $categorySlug === $cat['slug'] ? 'filter-chip--active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Фільтр по ціні -->
    <form class="price-filter" method="GET" action="<?= BASE_URL ?>/catalog">
        <?php if (!empty($categorySlug)): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($categorySlug) ?>">
        <?php endif; ?>
        <div class="price-filter__inputs">
            <label class="price-filter__label">
                Від
                <input
                        type="number"
                        name="price_min"
                        min="0"
                        step="10"
                        placeholder="0"
                        value="<?= htmlspecialchars($priceMin ?? '') ?>"
                        class="price-filter__input"
                >
            </label>
            <label class="price-filter__label">
                До
                <input
                        type="number"
                        name="price_max"
                        min="0"
                        step="10"
                        placeholder="9999"
                        value="<?= htmlspecialchars($priceMax ?? '') ?>"
                        class="price-filter__input"
                >
            </label>
            <button type="submit" class="btn btn-primary">Застосувати</button>
            <?php if ($priceMin || $priceMax): ?>
                <a href="<?= BASE_URL ?>/catalog<?= $categorySlug ? '?category=' . htmlspecialchars($categorySlug) : '' ?>"
                   class="btn btn-ghost">Скинути</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Список товарів -->
<?php if (!empty($products)): ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <a href="<?= BASE_URL ?>/catalog/<?= htmlspecialchars($product['slug']) ?>"
               class="product-card">
                <?php if (($product['stock'] ?? 0) <= 0): ?>
                    <span class="product-card__badge product-card__badge--out">Немає в наявності</span>
                <?php endif; ?>
                <div class="product-card__image">
                    <?php if (!empty($product['image'])): ?>
                        <img
                                src="<?= htmlspecialchars($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                loading="lazy"
                                width="300"
                                height="300"
                        >
                    <?php else: ?>
                        <div class="product-card__no-image" aria-hidden="true">
                            <svg viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="8" fill="currentColor" opacity=".3"/>
                                <ellipse cx="32" cy="16" rx="6" ry="10" fill="currentColor" opacity=".2"/>
                                <ellipse cx="32" cy="48" rx="6" ry="10" fill="currentColor" opacity=".2"/>
                                <ellipse cx="16" cy="32" rx="10" ry="6" fill="currentColor" opacity=".2"/>
                                <ellipse cx="48" cy="32" rx="10" ry="6" fill="currentColor" opacity=".2"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="product-card__body">
                    <p class="product-card__category">
                        <?= htmlspecialchars($product['category_name'] ?? '') ?>
                    </p>
                    <h3 class="product-card__name">
                        <?= htmlspecialchars($product['name']) ?>
                    </h3>
                    <p class="product-card__price">
                        <?= number_format($product['price'], 2, '.', ' ') ?> грн
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Пагінація -->
    <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
        <nav class="pagination" aria-label="Навігація по сторінках">
            <?php if ($pagination['current_page'] > 1): ?>
                <a href="<?= BASE_URL ?>/catalog?page=<?= $pagination['current_page'] - 1 ?>"
                   class="pagination__btn" aria-label="Попередня сторінка">
                    ←
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="<?= BASE_URL ?>/catalog?page=<?= $i ?>"
                   class="pagination__btn <?= $i === $pagination['current_page'] ? 'pagination__btn--active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="<?= BASE_URL ?>/catalog?page=<?= $pagination['current_page'] + 1 ?>"
                   class="pagination__btn" aria-label="Наступна сторінка">
                    →
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

<?php else: ?>
    <div class="empty-state">
        <div class="empty-state__icon" aria-hidden="true">
            <svg viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="8" fill="currentColor" opacity=".3"/>
                <ellipse cx="32" cy="16" rx="6" ry="10" fill="currentColor" opacity=".2"/>
                <ellipse cx="32" cy="48" rx="6" ry="10" fill="currentColor" opacity=".2"/>
                <ellipse cx="16" cy="32" rx="10" ry="6" fill="currentColor" opacity=".2"/>
                <ellipse cx="48" cy="32" rx="10" ry="6" fill="currentColor" opacity=".2"/>
            </svg>
        </div>
        <h2 class="empty-state__title">Товарів не знайдено</h2>
        <p class="empty-state__desc">Спробуйте змінити фільтри або перегляньте весь каталог</p>
        <a href="<?= BASE_URL ?>/catalog" class="btn btn-primary">Весь каталог</a>
    </div>
<?php endif; ?>