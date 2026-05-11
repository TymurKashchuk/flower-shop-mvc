<?php
/** @var int $orderId */
?>

<div class="checkout-success">
    <div class="checkout-success__icon">
        <svg viewBox="0 0 64 64" fill="none" aria-hidden="true">
            <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2"/>
            <path d="M20 32l9 9 15-16" stroke="currentColor" stroke-width="2.5"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <h1 class="checkout-success__title">Дякуємо за замовлення!</h1>
    <p class="checkout-success__order">Замовлення <strong>#<?= (int)$orderId ?></strong> успішно прийнято.</p>
    <p class="checkout-success__sub">Ми зв'яжемося з вами найближчим часом для підтвердження доставки.</p>
    <div class="checkout-success__actions">
        <a href="<?= BASE_URL ?>/catalog" class="btn btn-primary">Продовжити покупки</a>
        <?php if (!empty($_SESSION['user'])): ?>
            <a href="<?= BASE_URL ?>/profile" class="btn btn-ghost">Мій профіль</a>
        <?php endif; ?>
    </div>
</div>