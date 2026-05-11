<?php
/** @var array $items */
/** @var float $total */
?>

<div class="checkout-page">
    <h1 class="cart-page__title">Оформлення замовлення</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="checkout-layout">

        <form action="<?= BASE_URL ?>/checkout/store" method="POST" class="checkout-form">

            <div class="form-group">
                <label class="form-label" for="name">Імʼя та прізвище</label>
                <input class="form-input" type="text" id="name" name="name" required
                       placeholder="Іван Іваненко"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Телефон</label>
                <input class="form-input" type="tel" id="phone" name="phone" required
                       placeholder="093 000 00 00"
                       maxlength="10"
                       pattern="[0-9]*"
                       inputmode="numeric"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>

            <div class="form-group" id="address-group">
                <label class="form-label" for="address">Адреса доставки</label>
                <textarea class="form-input" id="address" name="address" rows="3" required
                          placeholder="м. Київ, вул. Хрещатик, 1" style="resize: none"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Спосіб доставки</label>
                <div class="checkout-delivery">
                    <label class="checkout-delivery__option">
                        <input type="radio" name="delivery_type" value="courier"
                                <?= (($_POST['delivery_type'] ?? 'courier') === 'courier') ? 'checked' : '' ?>>
                        <span>Кур'єр</span>
                    </label>

                    <label class="checkout-delivery__option">
                        <input type="radio" name="delivery_type" value="pickup"
                                <?= (($_POST['delivery_type'] ?? '') === 'pickup') ? 'checked' : '' ?>>
                        <span>Самовивіз</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary checkout-form__submit">
                Підтвердити замовлення
            </button>

        </form>

        <aside class="cart-summary">
            <h2 class="cart-summary__title">Ваше замовлення</h2>
            <ul style="list-style:none;padding:0;margin-bottom:var(--space-4)">
                <?php foreach ($items as $item): ?>
                    <div class="cart-summary__row">
                        <span><?= htmlspecialchars($item['name']) ?> &times; <?= (int)$item['quantity'] ?></span>
                        <span><?= number_format($item['price'] * $item['quantity'], 2, '.', ' ') ?> грн</span>
                    </div>
                <?php endforeach; ?>
            </ul>
            <div class="cart-summary__row cart-summary__row--total">
                <span>Разом:</span>
                <strong><?= number_format($total, 2, '.', ' ') ?> грн</strong>
            </div>
            <a href="<?= BASE_URL ?>/cart" class="btn btn-ghost"
               style="width:100%;justify-content:center;margin-top:var(--space-3)">
                &larr; Повернутись до кошика
            </a>
        </aside>

    </div>
</div>