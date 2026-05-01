<?php
/** @var array $items */
/** @var float $total */
?>

<?php if (empty($items)): ?>

    <div class="cart-empty">
        <svg viewBox="0 0 80 80" fill="none" aria-hidden="true">
            <path d="M16 8L8 20v44a4 4 0 004 4h56a4 4 0 004-4V20L64 8H16z"
                  stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            <path d="M8 20h64M52 32a12 12 0 01-24 0"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <h2>Кошик порожній</h2>
        <p>Додайте квіти з каталогу — і вони з'являться тут</p>
        <a href="<?= BASE_URL ?>/catalog" class="btn btn-primary">Перейти до каталогу</a>
    </div>

<?php else: ?>

    <div class="cart-page">
        <h1 class="cart-page__title">Кошик</h1>
        <div class="cart-page__inner">
            <div class="cart-list" id="cart-list">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item" id="cart-item-<?= $item['id'] ?>">
                        <div class="cart-item__img-wrap">
                            <?php if (!empty($item['image'])): ?>
                                <img
                                        src="<?= htmlspecialchars($item['image']) ?>"
                                        alt="<?= htmlspecialchars($item['name']) ?>"
                                        width="96" height="96"
                                        loading="lazy"
                                >
                            <?php else: ?>
                                <div class="cart-item__no-img" aria-hidden="true">
                                    <svg viewBox="0 0 40 40" fill="none">
                                        <circle cx="20" cy="20" r="5" fill="currentColor" opacity=".3"/>
                                        <ellipse cx="20" cy="8" rx="4" ry="7" fill="currentColor" opacity=".2"/>
                                        <ellipse cx="20" cy="32" rx="4" ry="7" fill="currentColor" opacity=".2"/>
                                        <ellipse cx="8" cy="20" rx="7" ry="4" fill="currentColor" opacity=".2"/>
                                        <ellipse cx="32" cy="20" rx="7" ry="4" fill="currentColor" opacity=".2"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="cart-item__info">
                            <a href="<?= BASE_URL ?>/catalog/<?= htmlspecialchars($item['slug']) ?>"
                               class="cart-item__name">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                            <p class="cart-item__price-unit">
                                <?= number_format($item['price'], 2, '.', ' ') ?> грн / шт
                            </p>
                        </div>

                        <!-- Кількість -->
                        <div class="quantity-control">
                            <button type="button"
                                    class="quantity-control__btn"
                                    data-action="decrease"
                                    data-item-id="<?= $item['id'] ?>"
                                    aria-label="Зменшити">
                                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                            <input
                                    type="number"
                                    class="quantity-control__input"
                                    value="<?= (int)$item['quantity'] ?>"
                                    min="1"
                                    max="<?= (int)$item['stock'] ?>"
                                    data-item-id="<?= $item['id'] ?>"
                                    aria-label="Кількість"
                            >
                            <button type="button"
                                    class="quantity-control__btn"
                                    data-action="increase"
                                    data-item-id="<?= $item['id'] ?>"
                                    aria-label="Збільшити">
                                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Сума по позиції -->
                        <p class="cart-item__subtotal"
                           id="subtotal-<?= $item['id'] ?>">
                            <?= number_format($item['price'] * $item['quantity'], 2, '.', ' ') ?> грн
                        </p>

                        <!-- Видалити -->
                        <button type="button"
                                class="cart-item__remove"
                                data-item-id="<?= $item['id'] ?>"
                                aria-label="Видалити товар">
                            <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5 5l10 10M15 5L5 15"
                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Загальну сума і кнопка оформлення-->
            <aside class="cart-summary">
                <h2 class="cart-summary__title">Підсумок</h2>
                <div class="cart-summary__row">
                    <span>Товарів:</span>
                    <span id="summary-count">
                    <?= count($items) ?> поз.
                </span>
                </div>
                <div class="cart-summary__row cart-summary__row--total">
                    <span>Разом:</span>
                    <strong id="summary-total">
                        <?= number_format($total, 2, '.', ' ') ?> грн
                    </strong>
                </div>
                <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary cart-summary__checkout">
                    Оформити замовлення
                </a>
                <form action="<?= BASE_URL ?>/cart/clear" method="POST" class="cart-summary__clear-form">
                    <button type="submit" class="btn btn-ghost cart-summary__clear">
                        Очистити кошик
                    </button>
                </form>
            </aside>
        </div>
    </div>
<?php endif; ?>
