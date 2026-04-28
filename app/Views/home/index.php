<section class="hero">
    <div class="hero__content">
        <p class="hero__label">Свіжі квіти щодня</p>
        <h1 class="hero__title">Квіти, що<br>говорять серцем</h1>
        <p class="hero__desc">Букети, композиції та живі рослини з доставкою по Житомиру</p>
        <div class="hero__actions">
            <a href="<?= BASE_URL ?>/catalog" class="btn btn-primary">Переглянути каталог</a>
            <a href="<?= BASE_URL ?>/catalog" class="btn btn-ghost">Дізнатись більше</a>
        </div>
    </div>
    <div class="hero__image">
        <div class="hero__image-wrap">
            <svg viewBox="0 0 400 500" fill="none" aria-hidden="true" class="hero__svg">
                <ellipse cx="200" cy="250" rx="160" ry="200" fill="#fce4ec" opacity=".5"/>
                <circle cx="200" cy="180" r="60" fill="#f48fb1" opacity=".6"/>
                <ellipse cx="200" cy="120" rx="30" ry="50" fill="#c0396b" opacity=".7"/>
                <ellipse cx="200" cy="240" rx="30" ry="50" fill="#c0396b" opacity=".7"/>
                <ellipse cx="140" cy="180" rx="50" ry="30" fill="#c0396b" opacity=".7"/>
                <ellipse cx="260" cy="180" rx="50" ry="30" fill="#c0396b" opacity=".7"/>
                <ellipse cx="155" cy="135" rx="30" ry="50" fill="#e91e8c" opacity=".4" transform="rotate(-45 155 135)"/>
                <ellipse cx="245" cy="135" rx="30" ry="50" fill="#e91e8c" opacity=".4" transform="rotate(45 245 135)"/>
                <ellipse cx="155" cy="225" rx="30" ry="50" fill="#e91e8c" opacity=".4" transform="rotate(45 155 225)"/>
                <ellipse cx="245" cy="225" rx="30" ry="50" fill="#e91e8c" opacity=".4" transform="rotate(-45 245 225)"/>
                <circle cx="200" cy="180" r="20" fill="#fff" opacity=".8"/>
                <rect x="190" y="240" width="20" height="120" rx="10" fill="#81c784" opacity=".8"/>
                <ellipse cx="170" cy="320" rx="25" ry="15" fill="#66bb6a" opacity=".6" transform="rotate(-20 170 320)"/>
                <ellipse cx="230" cy="340" rx="25" ry="15" fill="#66bb6a" opacity=".6" transform="rotate(20 230 340)"/>
            </svg>
        </div>
    </div>
</section>

<!-- Категорії -->
<?php if (!empty($categories)): ?>
    <section class="section">
        <h2 class="section__title">Категорії</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>/catalog?category=<?= htmlspecialchars($cat['slug']) ?>"
                   class="category-card">
                    <span class="category-card__name"><?= htmlspecialchars($cat['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<!-- Новинки -->
<?php if (!empty($featured)): ?>
    <section class="section">
        <div class="section__header">
            <h2 class="section__title">Нові надходження</h2>
            <a href="<?= BASE_URL ?>/catalog" class="section__link">Всі товари →</a>
        </div>
        <div class="products-grid">
            <?php foreach ($featured as $product): ?>
                <a href="<?= BASE_URL ?>/catalog/<?= htmlspecialchars($product['slug']) ?>"
                   class="product-card">
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
    </section>
<?php endif; ?>