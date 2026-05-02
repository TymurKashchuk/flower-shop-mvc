<?php
/** @var array $errors */
/** @var array $old */
?>

<div class="auth-page">
    <div class="auth-card">
        <h1 class="auth-card__title">Реєстрація</h1>
        <form class="auth-form" action="<?= BASE_URL ?>/register" method="POST" novalidate>
            <div class="form-group <?= !empty($errors['name']) ? 'form-group--error' : '' ?>">
                <label for="name" class="form-label">Ім'я</label>
                <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input"
                        value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                        autocomplete="name"
                        required
                >
                <?php if (!empty($errors['name'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group <?= !empty($errors['email']) ? 'form-group--error' : '' ?>">
                <label for="email" class="form-label">Email</label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        autocomplete="off"
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
                        autocomplete="new-password"
                        required
                >
                <?php if (!empty($errors['password'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group <?= !empty($errors['password_confirm']) ? 'form-group--error' : '' ?>">
                <label for="password_confirm" class="form-label">Повторіть пароль</label>
                <input
                        type="password"
                        id="password_confirm"
                        name="password_confirm"
                        class="form-input"
                        autocomplete="new-password"
                        required
                >
                <?php if (!empty($errors['password_confirm'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['password_confirm']) ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary auth-form__submit">Зареєструватись</button>
        </form>
        <p class="auth-card__switch">
            Вже є акаунт? <a href="<?= BASE_URL ?>/login">Увійти</a>
        </p>
    </div>
</div>