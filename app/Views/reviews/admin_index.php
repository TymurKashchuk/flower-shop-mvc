<?php /** @var array $items */ ?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Відгуки клієнтів</h2>
        <p class="admin-page-sub">Модерація відгуків на товари та відповіді від імені салону (<?= count($items) ?>)</p>
    </div>
</div>

<div class="admin-toolbar">
    <div class="admin-search">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="9" r="6"/>
            <path d="M19 19l-4.5-4.5"/>
        </svg>
        <input type="text"
               class="admin-search__input"
               placeholder="Пошук за клієнтом, товаром чи текстом..."
               data-admin-search="#reviewsTable">
    </div>

    <div class="admin-filter-group" data-filter-group="#reviewsTable">
        <button type="button" class="admin-filter-btn active" data-filter="all">Всі відгуки</button>
        <button type="button" class="admin-filter-btn" data-filter="pending">Очікують модерації</button>
        <button type="button" class="admin-filter-btn" data-filter="active">Опубліковані</button>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table" id="reviewsTable">
        <thead>
            <tr>
                <th>Клієнт</th>
                <th>Товар</th>
                <th>Оцінка</th>
                <th style="min-width:300px">Відгук клієнта</th>
                <th>Дата</th>
                <th>Статус</th>
                <th style="text-align:right">Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="7" class="admin-table-empty">
                        <p>Відгуків ще немає</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $r): ?>
                <tr data-status="<?= $r['is_active'] ? 'active' : 'pending' ?>">
                    <td>
                        <div style="font-weight:600"><?= htmlspecialchars($r['user_name'] ?? 'Анонім') ?></div>
                        <span style="font-size:0.75rem;color:var(--admin-text-muted)">ID: #<?= (int)$r['id'] ?></span>
                    </td>
                    <td>
                        <strong style="color:var(--admin-text-main)"><?= htmlspecialchars($r['product_name'] ?? '—') ?></strong>
                    </td>
                    <td>
                        <span style="display:inline-flex;gap:2px;color:#f59e0b" aria-label="<?= (int)$r['rating'] ?> з 5">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <svg viewBox="0 0 20 20" fill="<?= $s <= (int)$r['rating'] ? 'currentColor' : '#e2e8f0' ?>" width="15" height="15">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </span>
                    </td>
                    <td>
                        <div style="font-size:0.875rem;line-height:1.5;color:var(--admin-text-main)">
                            <?= nl2br(htmlspecialchars($r['text'])) ?>
                        </div>

                        <!-- Published store reply badge -->
                        <?php if (!empty($r['admin_reply'])): ?>
                            <div style="background:var(--admin-primary-light);border-left:3px solid var(--admin-primary);border-radius:0 var(--admin-radius-sm) var(--admin-radius-sm) 0;padding:8px 12px;margin-top:10px;font-size:0.825rem">
                                <div style="font-size:0.7rem;font-weight:700;color:var(--admin-primary);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:2px;display:flex;align-items:center;gap:6px">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                    </svg>
                                    Відповідь салону:
                                </div>
                                <div style="color:var(--admin-text-main);line-height:1.4">
                                    <?= nl2br(htmlspecialchars($r['admin_reply'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--admin-text-muted)">
                        <?= date('d.m.Y', strtotime($r['created_at'])) ?>
                    </td>
                    <td>
                        <?php if ($r['is_active']): ?>
                            <span class="admin-badge admin-badge--success">Опубліковано</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--warning">Очікує</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="admin-actions-cell">
                            <!-- Button to trigger pop-up modal -->
                            <button type="button"
                                    class="admin-btn admin-btn--sm admin-btn--secondary btn-open-reply-modal"
                                    data-review-id="<?= (int)$r['id'] ?>"
                                    data-action-url="<?= BASE_URL ?>/admin/reviews/<?= (int)$r['id'] ?>/reply"
                                    data-user-name="<?= htmlspecialchars($r['user_name'] ?? 'Анонім', ENT_QUOTES) ?>"
                                    data-product-name="<?= htmlspecialchars($r['product_name'] ?? '—', ENT_QUOTES) ?>"
                                    data-rating="<?= (int)$r['rating'] ?>"
                                    data-review-text="<?= htmlspecialchars($r['text'], ENT_QUOTES) ?>"
                                    data-reply-text="<?= htmlspecialchars($r['admin_reply'] ?? '', ENT_QUOTES) ?>"
                                    title="<?= !empty($r['admin_reply']) ? 'Редагувати відповідь' : 'Написати відповідь' ?>">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <path d="M7 8h6M7 12h4m-7 5l3-3h7a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2h2v4z"/>
                                </svg>
                                <?= !empty($r['admin_reply']) ? 'Змінити' : 'Відповісти' ?>
                            </button>

                            <?php if (!$r['is_active']): ?>
                                <form method="POST" action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/approve">
                                    <button class="admin-btn admin-btn--sm admin-btn--success" title="Схвалити і опублікувати">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Схвалити
                                    </button>
                                </form>
                            <?php endif; ?>

                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/reviews/<?= $r['id'] ?>/delete"
                                  onsubmit="return confirm('Видалити цей відгук?')">
                                <button class="admin-btn admin-btn--sm admin-btn--danger" title="Видалити">
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <path d="M6 7h8m-7 0v8a1 1 0 001 1h4a1 1 0 001-1V7m-5-3h2a1 1 0 011 1v2H7V5a1 1 0 011-1z"/>
                                    </svg>
                                    Видалити
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ==========================================================================
     REPLY POPUP MODAL WITH BACKDROP BLUR
     ========================================================================== -->
<div class="admin-modal-backdrop" id="reviewReplyBackdrop">
    <div class="admin-modal" id="reviewReplyModal">
        <div class="admin-modal__header">
            <h3 class="admin-modal__title">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" width="20" height="20" style="color:var(--admin-primary)">
                    <path d="M7 8h6M7 12h4m-7 5l3-3h7a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2h2v4z"/>
                </svg>
                Відповідь на відгук від імені магазину
            </h3>
            <button type="button" class="admin-modal__close" data-close-reply-modal aria-label="Закрити">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M6 6l8 8M14 6l-8 8" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <form method="POST" id="reviewReplyForm" action="">
            <div class="admin-modal__body">
                <!-- Quote of the customer review -->
                <div class="admin-modal-review-quote">
                    <div class="admin-modal-review-quote__author">
                        <span id="modalReplyAuthor" style="font-size:0.95rem">Ім'я клієнта</span>
                        <span id="modalReplyRating" style="display:inline-flex;gap:2px;color:#f59e0b"></span>
                    </div>
                    <div class="admin-modal-review-quote__product">
                        Товар: <span id="modalReplyProduct">—</span>
                    </div>
                    <div class="admin-modal-review-quote__text" id="modalReplyQuote">
                        Текст відгуку...
                    </div>
                </div>

                <div class="admin-form-group" style="margin-bottom:0">
                    <label class="admin-form-label" for="modalReplyTextarea">
                        Ваша офіційна відповідь клієнту *
                    </label>
                    <textarea name="reply"
                              id="modalReplyTextarea"
                              class="admin-textarea"
                              rows="5"
                              placeholder="Дякуємо за ваш відгук! Нам дуже приємно, що букет сподобався..."
                              required></textarea>
                    <span class="admin-form-hint" style="margin-top:6px">
                        Ця відповідь буде опублікована на сторінці товару під відгуком клієнта.
                    </span>
                </div>
            </div>

            <div class="admin-modal__footer">
                <button type="button" class="admin-btn admin-btn--secondary" data-close-reply-modal>
                    Скасувати
                </button>
                <button type="submit" class="admin-btn admin-btn--primary">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Опублікувати відповідь
                </button>
            </div>
        </form>
    </div>
</div>