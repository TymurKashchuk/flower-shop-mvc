<?php
/** @var array $product */
/** @var array $categories */
?>
<div class="product-page">
    <nav class="breadcrumb" aria-label="Навігація">
        <a href="<?= BASE_URL ?>">Головна</a>
        <span class="breadcrumb__sep" aria-hidden="true">›</span>
        <a href="<?= BASE_URL ?>/catalog">Каталог</a>
        <span class="breadcrumb__sep" aria-hidden="true">›</span>
        <span aria-current="page"><?= htmlspecialchars($product['name']) ?></span>
    </nav>

    <div class="product-page__inner">
        <!-- Фото товару -->
        <div class="product-page__gallery">
            <?php if (!empty($product['image'])): ?>
                <img
                        src="<?= htmlspecialchars($product['image']) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>"
                        class="product-page__img"
                        width="600"
                        height="600"
                >
            <?php else: ?>
                <div class="product-page__no-image" aria-hidden="true">
                    <svg viewBox="0 0 120 120" fill="none">
                        circle cx="60" cy="60" r="16" fill="currentColor" opacity=".3"/>
                        <ellipse cx="60" cy="30" rx="11" ry="19" fill="currentColor" opacity=".2"/>
                        <ellipse cx="60" cy="90" rx="11" ry="19" fill="currentColor" opacity=".2"/>
                        <ellipse cx="30" cy="60" rx="19" ry="11" fill="currentColor" opacity=".2"/>
                        <ellipse cx="90" cy="60" rx="19" ry="11" fill="currentColor" opacity=".2"/>
                        <ellipse cx="38" cy="38" rx="11" ry="19" fill="currentColor" opacity=".15"
                                 transform="rotate(-45 38 38)"/>
                        <ellipse cx="82" cy="38" rx="11" ry="19" fill="currentColor" opacity=".15"
                                 transform="rotate(45 82 38)"/>
                        <ellipse cx="38" cy="82" rx="11" ry="19" fill="currentColor" opacity=".15"
                                 transform="rotate(45 38 82)"/>
                        <ellipse cx="82" cy="82" rx="11" ry="19" fill="currentColor" opacity=".15"
                                 transform="rotate(-45 82 82)"/>
                    </svg>
                </div>
            <?php endif; ?>
        </div>

        <!-- Інформація про товар -->
        <div class="product-page__info">

            <?php if (!empty($product['category_name'])): ?>
                <p class="product-page__category">
                    <a href="<?= BASE_URL ?>/catalog?category=<?= htmlspecialchars($product['category_slug'] ?? '') ?>">
                        <?= htmlspecialchars($product['category_name']) ?>
                    </a>
                </p>
            <?php endif; ?>

            <h1 class="product-page__title">
                <?= htmlspecialchars($product['name']) ?>
            </h1>

            <p class="product-page__price">
                <?= number_format($product['price'], 2, '.', ' ') ?> грн
            </p>

            <?php if (!empty($product['description'])): ?>
                <div class="product-page__desc">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </div>
            <?php endif; ?>

            <!-- Кількість + кошик -->
            <form class="product-page__order" action="<?= BASE_URL ?>/cart/add" method="POST">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

                <div class="quantity-control">
                    <button type="button" class="quantity-control__btn" id="qty-minus" aria-label="Зменшити">
                        <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <input
                            type="number"
                            name="quantity"
                            id="quantity"
                            value="1"
                            min="1"
                            max="99"
                            class="quantity-control__input"
                            aria-label="Кількість"
                    >
                    <button type="button" class="quantity-control__btn" id="qty-plus" aria-label="Збільшити">
                        <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="btn btn-primary product-page__add-btn">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"
                              stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M3 6h18M16 10a4 4 0 01-8 0"
                              stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Додати в кошик
                </button>
            </form>

            <!-- Наявність -->
            <p class="product-page__stock
                <?= !empty($product['in_stock']) ? 'product-page__stock--in' : 'product-page__stock--out' ?>">
                <?php if (!empty($product['in_stock'])): ?>
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M6.5 10l2.5 2.5 4-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                    В наявності
                <?php else: ?>
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M7 7l6 6M13 7l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Немає в наявності
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>