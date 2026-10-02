<?php
/** @var array $product */
/** @var array $categories */
$reviews     = $reviews ?? [];
$avgRating   = $avgRating ?? 0.0;
$hasReviewed = $hasReviewed ?? false;
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
                        <circle cx="60" cy="60" r="16" fill="currentColor" opacity=".3"/>
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
            <form id="add-to-cart-form" class="product-page__order" action="<?= BASE_URL ?>/cart/add" method="POST">
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
                <?= ($product['stock'] ?? 0) > 0 ? 'product-page__stock--in' : 'product-page__stock--out' ?>">
                <?php if (($product['stock'] ?? 0) > 0): ?>
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M6.5 10l2.5 2.5 4-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                    В наявності
                <?php else: ?>
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M7 7l6 6M13 7l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Немає в наявності
                <?php endif; ?>
            </p>
        </div>
    </div>
    <!-- Відгуки -->
    <section class="reviews" aria-labelledby="reviews-title">
        <div class="reviews__header">
            <h2 class="reviews__title" id="reviews-title">
                Відгуки
                <?php if ($avgRating > 0): ?>
                    <span class="reviews__avg">
                    <?= $avgRating ?>
                    <span class="review-stars review-stars--sm">
                        <?php
                        $full = floor($avgRating);
                        $half = ($avgRating - $full) >= 0.5;
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $full ? '★' : ($half && $i == $full + 1 ? '★' : '☆');
                        }
                        ?>
                    </span>
                    <span class="reviews__count">(<?= count($reviews) ?>)</span>
                </span>
                <?php endif; ?>
            </h2>
        </div>

        <?php if (!empty($reviews)): ?>
            <div class="reviews__list">
                <?php foreach ($reviews as $r): ?>
                    <div class="review-card">
                        <div class="review-card__head">
                            <div class="review-card__avatar">
                                <?= mb_strtoupper(mb_substr($r['user_name'] ?? 'А', 0, 1)) ?>
                            </div>
                            <div>
                                <strong class="review-card__name">
                                    <?= htmlspecialchars($r['user_name'] ?? 'Анонім') ?>
                                </strong>
                                <time class="review-card__date">
                                    <?= date('d.m.Y', strtotime($r['created_at'])) ?>
                                </time>
                            </div>
                            <span class="review-stars review-stars--gold">
                            <?= str_repeat('★', (int)$r['rating']) ?>
                            <?= str_repeat('☆', 5 - (int)$r['rating']) ?>
                        </span>
                        </div>
                        <p class="review-card__text">
                            <?= htmlspecialchars($r['text']) ?>
                        </p>
                        <?php if (!empty($r['admin_reply'])): ?>
                            <div class="review-card__reply">
                                <div class="review-card__reply-label">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true" width="14" height="14">
                                        <path d="M14 2H2v9h3v3l4-3h5V2z" stroke="currentColor"
                                              stroke-width="1.5" stroke-linejoin="round"/>
                                    </svg>
                                    Відповідь магазину
                                </div>
                                <p><?= htmlspecialchars($r['admin_reply']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="reviews__empty">Будьте першим, хто залишить відгук!</p>
        <?php endif; ?>

        <!-- Форма відгуку -->
        <?php if (!empty($_SESSION['user']) && !$hasReviewed): ?>
            <div class="review-form-wrap">
                <h3 class="review-form__title">Залишити відгук</h3>
                <form class="review-form" method="POST"
                      action="<?= BASE_URL ?>/catalog/<?= htmlspecialchars($product['slug']) ?>/reviews">

                    <div class="form-group">
                        <label class="form-label">Оцінка</label>
                        <div class="star-rating" role="group" aria-label="Оцінка">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="rating" id="star<?= $i ?>"
                                       value="<?= $i ?>" required>
                                <label for="star<?= $i ?>" aria-label="<?= $i ?> зірок">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="review-text" class="form-label">Ваш відгук</label>
                        <textarea id="review-text" name="text" class="form-input"
                                  rows="4" minlength="5" required
                                  placeholder="Поділіться враженнями..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Надіслати відгук
                    </button>
                </form>
            </div>
        <?php elseif (!empty($_SESSION['user']) && $hasReviewed): ?>
            <p class="reviews__already">Ви вже залишили відгук на цей товар.</p>
        <?php else: ?>
            <p class="reviews__login">
                <a href="<?= BASE_URL ?>/login">Увійдіть</a>, щоб залишити відгук.
            </p>
        <?php endif; ?>
    </section>
</div>