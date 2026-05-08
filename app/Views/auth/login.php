<?php
/** @var array $errors */
/** @var array $old */
?>
<div class="auth-page">
    <div class="auth-card">
        <h1 class="auth-card__title">Вхід</h1>

        <?php if (isset($_GET['banned'])): ?>
            <div class="alert alert-error">
                Ваш акаунт заблоковано. Зверніться до адміністратора.
                <a href="mailto:admin@flower.com">admin@flower.com</a>
            </div>
        <?php endif; ?>

        <form class="auth-form" action="<?= BASE_URL ?>/login" method="POST" novalidate>
            <div class="form-group <?= !empty($errors['email']) ? 'form-group--error' : '' ?>">
                <label for="email" class="form-label">Email</label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        autocomplete="email"
                        required
                >
                <?php if (!empty($errors['email'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group <?= !empty($errors['password']) ? 'form-group--error' : '' ?>">
                <label for="password" class="form-label">Пароль</label>
                <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        autocomplete="current-password"
                        required
                >
                <?php if (!empty($errors['password'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary auth-form__submit">Увійти</button>
        </form>
        <p class="auth-card__switch">
            Немає акаунту? <a href="<?= BASE_URL ?>/register">Зареєструватись</a>
        </p>
    </div>
</div>