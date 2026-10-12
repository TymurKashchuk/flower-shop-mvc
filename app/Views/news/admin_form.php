<?php
/** @var array|null $item */
/** @var array $errors */
/** @var array $old */
$errors ??= [];
$old ??= [];
$isEdit = $item !== null;
$action = $isEdit
        ? BASE_URL . '/admin/news/' . $item['id'] . '/update'
        : BASE_URL . '/admin/news/store';
$pageTitle = $isEdit ? 'Редагувати новину' : 'Додати новину';
$currentImage = $old['image'] ?? ($item['image'] ?? '');
?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title"><?= htmlspecialchars($pageTitle) ?></h2>
        <p class="admin-page-sub"><?= $isEdit ? htmlspecialchars($item['title']) : 'Створення публікації або акційної пропозиції' ?></p>
    </div>
    <div style="display:flex;gap:10px">
        <?php if ($isEdit && !empty($item['slug'])): ?>
            <a href="<?= BASE_URL ?>/news/<?= htmlspecialchars($item['slug']) ?>"
               target="_blank"
               class="admin-btn admin-btn--secondary">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path d="M2.458 10C3.732 6.943 6.632 4.5 10 4.5s6.268 2.443 7.542 5.5c-1.274 3.057-4.174 5.5-7.542 5.5S3.732 13.057 2.458 10z"/>
                </svg>
                Переглянути
            </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/admin/news" class="admin-btn admin-btn--secondary">
            &larr; До списку
        </a>
    </div>
</div>

<form action="<?= $action ?>" method="POST" class="admin-form-grid">
    <!-- Left Column: Content -->
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">Зміст публікації</h3>
        </div>
        <div class="admin-card__body">
            <div class="admin-form-group">
                <label class="admin-form-label" for="newsTitle">Заголовок новини *</label>
                <input type="text"
                       id="newsTitle"
                       name="title"
                       class="admin-input"
                       value="<?= htmlspecialchars($old['title'] ?? $item['title'] ?? '') ?>"
                       placeholder="Наприклад: Знижка -20% на всі півонії до Дня закоханих"
                       required>
                <?php if (!empty($errors['title'])): ?>
                    <span style="color:var(--admin-danger);font-size:0.75rem"><?= htmlspecialchars($errors['title']) ?></span>
                <?php endif; ?>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="newsContent">Текст новини / опис акції *</label>
                <textarea id="newsContent"
                          name="content"
                          class="admin-textarea"
                          rows="10"
                          placeholder="Детальний текст новини..."><?= htmlspecialchars($old['content'] ?? $item['content'] ?? '') ?></textarea>
                <?php if (!empty($errors['content'])): ?>
                    <span style="color:var(--admin-danger);font-size:0.75rem"><?= htmlspecialchars($errors['content']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Settings & Media -->
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">Публікація</h3>
            </div>
            <div class="admin-card__body">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="newsStatus">Статус</label>
                    <select name="is_active" id="newsStatus" class="admin-select">
                        <option value="1" <?= ($old['is_active'] ?? $item['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>
                            Опубліковано (видно всім)
                        </option>
                        <option value="0" <?= ($old['is_active'] ?? $item['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>
                            Чернетка (приховано)
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">Обкладинка</h3>
            </div>
            <div class="admin-card__body">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="newsImage">Посилання на зображення</label>
                    <input type="text"
                           id="newsImage"
                           name="image"
                           class="admin-input"
                           placeholder="https://..."
                           value="<?= htmlspecialchars($currentImage) ?>"
                           data-image-preview-input="newsImagePreview">
                    <span class="admin-form-hint">URL прямого посилання на банер новини</span>
                </div>

                <div class="admin-image-preview-box" id="newsImagePreview">
                    <?php if (!empty($currentImage)): ?>
                        <img src="<?= htmlspecialchars($currentImage) ?>" alt="Preview" onerror="this.style.display='none'">
                    <?php else: ?>
                        <div class="admin-image-preview-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="M21 15l-5-5L5 21"/>
                            </svg>
                            <span>Попередній перегляд обкладинки</span>
                        </div>
                        <img src="" alt="Preview" style="display:none">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="admin-btn admin-btn--primary" style="flex:1">
                <?= $isEdit ? 'Зберегти зміни' : 'Опублікувати' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/news" class="admin-btn admin-btn--secondary">
                Скасувати
            </a>
        </div>
    </div>
</form>