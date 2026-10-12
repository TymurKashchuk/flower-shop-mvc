<?php
/** @var array $item */
/** @var array $categories */
/** @var array $errors */
/** @var array $old */
$errors ??= [];
$old ??= [];

$currentImage = $old['image'] ?? $item['image'] ?? '';
?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Редагування товару #<?= (int)$item['id'] ?></h2>
        <p class="admin-page-sub"><?= htmlspecialchars($item['name']) ?></p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="<?= BASE_URL ?>/catalog/<?= htmlspecialchars($item['slug'] ?? '') ?>"
           target="_blank"
           class="admin-btn admin-btn--secondary"
           title="Переглянути на сайті">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path d="M2.458 10C3.732 6.943 6.632 4.5 10 4.5s6.268 2.443 7.542 5.5c-1.274 3.057-4.174 5.5-7.542 5.5S3.732 13.057 2.458 10z"/>
            </svg>
            На вітрині
        </a>
        <a href="<?= BASE_URL ?>/admin/products" class="admin-btn admin-btn--secondary">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="16" height="16">
                <path d="M16 10H4m0 0l4-4m-4 4l4 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            До списку
        </a>
    </div>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/products/<?= (int)$item['id'] ?>/update" class="admin-form-grid">
    
    <!-- Left Column: Primary info -->
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">Основна інформація</h3>
        </div>
        <div class="admin-card__body">
            <div class="admin-form-group">
                <label class="admin-form-label" for="productName">Назва товару *</label>
                <input type="text"
                       id="productName"
                       name="name"
                       class="admin-input"
                       value="<?= htmlspecialchars($old['name'] ?? $item['name']) ?>"
                       required>
                <?php if (!empty($errors['name'])): ?>
                    <span style="color:var(--admin-danger);font-size:0.75rem"><?= htmlspecialchars($errors['name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="productCategory">Категорія</label>
                <select name="category_id" id="productCategory" class="admin-select">
                    <option value="">— Оберіть категорію —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= ($old['category_id'] ?? $item['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['category_id'])): ?>
                    <span style="color:var(--admin-danger);font-size:0.75rem"><?= htmlspecialchars($errors['category_id']) ?></span>
                <?php endif; ?>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="productDescription">Опис товару</label>
                <textarea id="productDescription"
                          name="description"
                          class="admin-textarea"
                          rows="6"><?= htmlspecialchars($old['description'] ?? $item['description'] ?? '') ?></textarea>
                <span class="admin-form-hint">Детальний опис букета, догляду та компонентів</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Pricing, Inventory, Media & Status -->
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">Ціна та наявність</h3>
            </div>
            <div class="admin-card__body">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="productPrice">Ціна *</label>
                    <div class="admin-input-affix">
                        <input type="number"
                               id="productPrice"
                               name="price"
                               class="admin-input"
                               step="0.01"
                               min="0"
                               value="<?= htmlspecialchars($old['price'] ?? $item['price']) ?>"
                               required>
                        <span class="admin-input-affix__text">грн</span>
                    </div>
                    <?php if (!empty($errors['price'])): ?>
                        <span style="color:var(--admin-danger);font-size:0.75rem"><?= htmlspecialchars($errors['price']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="productStock">Залишок на складі</label>
                    <div class="admin-input-affix">
                        <input type="number"
                               id="productStock"
                               name="stock"
                               class="admin-input"
                               min="0"
                               value="<?= htmlspecialchars($old['stock'] ?? $item['stock']) ?>">
                        <span class="admin-input-affix__text">шт.</span>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="productStatus">Видимість у каталозі</label>
                    <select name="is_active" id="productStatus" class="admin-select">
                        <option value="1" <?= ($old['is_active'] ?? $item['is_active']) == 1 ? 'selected' : '' ?>>Активний (видно всім)</option>
                        <option value="0" <?= ($old['is_active'] ?? $item['is_active']) == 0 ? 'selected' : '' ?>>Прихований (чернетка)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">Фотографія</h3>
            </div>
            <div class="admin-card__body">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="productImage">Посилання на зображення</label>
                    <input type="text"
                           id="productImage"
                           name="image"
                           class="admin-input"
                           value="<?= htmlspecialchars($currentImage) ?>"
                           placeholder="https://..."
                           data-image-preview-input="productImageEditPreview">
                    <span class="admin-form-hint">Вкажіть пряме URL-посилання на фото букета</span>
                </div>

                <div class="admin-image-preview-box" id="productImageEditPreview">
                    <?php if (!empty($currentImage)): ?>
                        <img src="<?= htmlspecialchars($currentImage) ?>" alt="Preview" onerror="this.style.display='none'">
                    <?php else: ?>
                        <div class="admin-image-preview-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="M21 15l-5-5L5 21"/>
                            </svg>
                            <span>Попередній перегляд фото</span>
                        </div>
                        <img src="" alt="Preview" style="display:none">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="admin-btn admin-btn--primary" style="flex:1">
                Зберегти зміни
            </button>
            <a href="<?= BASE_URL ?>/admin/products" class="admin-btn admin-btn--secondary">
                Скасувати
            </a>
        </div>
    </div>
</form>