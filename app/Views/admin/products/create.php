<?php /** @var array $categories */
/** @var array $errors */
/** @var array $old */ ?>
<form method="POST" action="<?= BASE_URL ?>/admin/products/store" class="admin-form">

    <div class="form-group <?= !empty($errors['name']) ? 'form-group--error' : '' ?>">
        <label class="form-label">Назва</label>
        <input type="text" name="name" class="form-input"
               value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
        <?php if (!empty($errors['name'])): ?>
            <p class="form-error"><?= $errors['name'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form-group <?= !empty($errors['category_id']) ? 'form-group--error' : '' ?>">
        <label class="form-label">Категорія</label>
        <select name="category_id" class="form-input">
            <option value="">— Оберіть —</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= ($old['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['category_id'])): ?>
            <p class="form-error"><?= $errors['category_id'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form-group <?= !empty($errors['price']) ? 'form-group--error' : '' ?>">
        <label class="form-label">Ціна (грн)</label>
        <input type="number" name="price" class="form-input" step="0.01" min="0"
               value="<?= htmlspecialchars($old['price'] ?? '') ?>" required>
        <?php if (!empty($errors['price'])): ?>
            <p class="form-error"><?= $errors['price'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label class="form-label">Залишок</label>
        <input type="number" name="stock" class="form-input" min="0"
               value="<?= htmlspecialchars($old['stock'] ?? 0) ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Посилання на зображення</label>
        <input type="text" name="image" class="form-input"
               value="<?= htmlspecialchars($old['image'] ?? '') ?>"
               placeholder="https://...">
    </div>

    <div class="form-group">
        <label class="form-label">Опис</label>
        <textarea name="description" class="form-input" rows="4"
                  style="resize:vertical"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label class="form-label">Статус</label>
        <select name="is_active" class="form-input">
            <option value="1" <?= ($old['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Активний</option>
            <option value="0" <?= ($old['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Прихований</option>
        </select>
    </div>

    <div style="display:flex;gap:var(--space-3);margin-top:var(--space-6)">
        <button type="submit" class="btn btn-primary">Зберегти</button>
        <a href="<?= BASE_URL ?>/admin/products" class="btn">Скасувати</a>
    </div>
</form>