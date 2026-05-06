<?php /** @var array|null $item */
/** @var array $errors */
/** @var array $old */
$errors ??= [];
$old ??= [];
$isEdit = $item !== null;
$action = $isEdit
        ? BASE_URL . '/admin/news/' . $item['id'] . '/update'
        : BASE_URL . '/admin/news/store';
$title ??= $isEdit ? 'Редагувати новину' : 'Додати новину';
?>

<div style="max-width:680px;margin-inline:auto">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/admin/news">Новини</a>
        <span class="breadcrumb__sep">/</span>
        <span><?= $isEdit ? 'Редагувати' : 'Додати' ?></span>
    </div>

    <h1 style="font-family:var(--font-display);font-size:var(--text-xl);
               margin-bottom:var(--space-8)"><?= htmlspecialchars($title) ?></h1>

    <form action="<?= $action ?>" method="POST"
          style="display:flex;flex-direction:column;gap:var(--space-5)">

        <div class="form-group <?= !empty($errors['title']) ? 'form-group--error' : '' ?>">
            <label class="form-label">Заголовок</label>
            <input type="text" name="title" class="form-input"
                   value="<?= htmlspecialchars($old['title'] ?? $item['title'] ?? '') ?>" required>
            <?php if (!empty($errors['title'])): ?>
                <span class="form-error"><?= htmlspecialchars($errors['title']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= !empty($errors['content']) ? 'form-group--error' : '' ?>">
            <label class="form-label">Текст новини</label>
            <textarea name="content" class="form-input" rows="8"
                      style="resize:vertical"><?= htmlspecialchars($old['content'] ?? $item['content'] ?? '') ?></textarea>
            <?php if (!empty($errors['content'])): ?>
                <span class="form-error"><?= htmlspecialchars($errors['content']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">
                Зображення
                <span style="color:var(--color-text-muted);font-weight:400">(URL, необовʼязково)</span>
            </label>
            <input type="text" name="image" class="form-input"
                   placeholder="https://..."
                   value="<?= htmlspecialchars($old['image'] ?? $item['image'] ?? '') ?>">
        </div>

        <label style="display:flex;align-items:center;gap:var(--space-2);
                      font-size:var(--text-sm);cursor:pointer">
            <input type="checkbox" name="is_active" value="1"
                    <?= ($old['is_active'] ?? $item['is_active'] ?? 0) ? 'checked' : '' ?>>
            Опублікувати
        </label>

        <div style="display:flex;gap:var(--space-3)">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Зберегти' : 'Додати' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/news" class="btn btn-ghost">Скасувати</a>
        </div>
    </form>
</div>