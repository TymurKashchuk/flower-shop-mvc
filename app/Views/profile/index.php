<?php
/** @var array $user */
/** @var array $errors */
/** @var array $old */
?>
<div style="max-width:560px; margin-inline:auto; padding-block:var(--space-16)">

    <h1 class="page-title" style="margin-bottom:var(--space-8)">Мій профіль</h1>

    <div class="auth-card" style="padding:var(--space-10)">

        <div class="profile-avatar" style="margin-bottom:var(--space-8)">
            <?= mb_strtoupper(mb_substr($user['name'], 0, 1)) ?>
        </div>

        <form action="<?= BASE_URL ?>/profile/update" method="POST" novalidate
              style="display:flex; flex-direction:column; gap:var(--space-6)">

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label" for="name">Ім'я</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="<?= htmlspecialchars($old['name'] ?? $user['name']) ?>" required>
                <?php if (!empty($errors['name'])): ?>
                    <span class="form-error"><?= htmlspecialchars($errors['name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input"
                       value="<?= htmlspecialchars($old['email'] ?? $user['email']) ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <span class="form-error"><?= htmlspecialchars($errors['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label" for="password">
                    Новий пароль
                    <span style="color:var(--color-text-muted);font-weight:400">
                        (залиш порожнім, щоб не змінювати)
                    </span>
                </label>
                <input type="password" id="password" name="password" class="form-input"
                       autocomplete="new-password" placeholder="Мінімум 6 символів">
                <?php if (!empty($errors['password'])): ?>
                    <span class="form-error"><?= htmlspecialchars($errors['password']) ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary"
                    style="width:100%; margin-top:var(--space-2)">
                Зберегти зміни
            </button>

        </form>
    </div>
</div>