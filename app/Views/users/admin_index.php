<?php /** @var array $items */ ?>

<div class="admin-page-header">
    <div class="admin-page-header__left">
        <h2 class="admin-page-title">Користувачі</h2>
        <p class="admin-page-sub">Список зареєстрованих клієнтів та адміністраторів (<?= count($items) ?>)</p>
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
               placeholder="Пошук за ім'ям або email..."
               data-admin-search="#usersTable">
    </div>

    <div class="admin-filter-group" data-filter-group="#usersTable">
        <button type="button" class="admin-filter-btn active" data-filter="all">Всі</button>
        <button type="button" class="admin-filter-btn" data-filter="admin">Адміністратори</button>
        <button type="button" class="admin-filter-btn" data-filter="active">Активні</button>
        <button type="button" class="admin-filter-btn" data-filter="banned">Заблоковані</button>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table" id="usersTable">
        <thead>
            <tr>
                <th style="width:50px"></th>
                <th>Користувач</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Дата реєстрації</th>
                <th style="text-align:right">Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="7" class="admin-table-empty">
                        <p>Користувачів немає</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $u): ?>
                <?php
                $statusFilter = 'active';
                if ($u['role'] === 'admin') {
                    $statusFilter = 'admin';
                } elseif ($u['is_banned']) {
                    $statusFilter = 'banned';
                }
                ?>
                <tr data-status="<?= $statusFilter ?>">
                    <td>
                        <div style="width:34px;height:34px;border-radius:50%;background:var(--admin-surface-subtle);color:var(--admin-text-main);display:flex;align-items:center;justify-content:center;font-weight:600;font-size:0.85rem;border:1px solid var(--admin-border)">
                            <?= mb_strtoupper(mb_substr($u['name'] ?? 'U', 0, 1)) ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600"><?= htmlspecialchars($u['name']) ?></div>
                        <span style="font-size:0.75rem;color:var(--admin-text-muted)">ID: #<?= (int)$u['id'] ?></span>
                    </td>
                    <td>
                        <a href="mailto:<?= htmlspecialchars($u['email']) ?>" style="color:var(--admin-text-muted);text-decoration:none">
                            <?= htmlspecialchars($u['email']) ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($u['role'] === 'admin'): ?>
                            <span class="admin-badge admin-badge--info">Адмін</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--neutral">Клієнт</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($u['is_banned']): ?>
                            <span class="admin-badge admin-badge--danger">Заблоковано</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge--success">Активний</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--admin-text-muted)">
                        <?= date('d.m.Y', strtotime($u['created_at'])) ?>
                    </td>
                    <td>
                        <div class="admin-actions-cell">
                            <?php if ($u['role'] !== 'admin'): ?>
                                <?php if ($u['is_banned']): ?>
                                    <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/unban">
                                        <button class="admin-btn admin-btn--sm admin-btn--success" title="Розблокувати">
                                            Розблокувати
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST"
                                          action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/ban"
                                          onsubmit="return confirm('Заблокувати доступ користувачу <?= htmlspecialchars($u['name'], ENT_QUOTES) ?>?')">
                                        <button class="admin-btn admin-btn--sm admin-btn--secondary" style="color:var(--admin-warning)" title="Заблокувати">
                                            Заблокувати
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <form method="POST"
                                      action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/delete"
                                      onsubmit="return confirm('Видалити користувача <?= htmlspecialchars($u['name'], ENT_QUOTES) ?>? Це незворотно.')">
                                    <button class="admin-btn admin-btn--sm admin-btn--danger" title="Видалити акаунт">
                                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                                            <path d="M6 7h8m-7 0v8a1 1 0 001 1h4a1 1 0 001-1V7m-5-3h2a1 1 0 011 1v2H7V5a1 1 0 011-1z"/>
                                        </svg>
                                        Видалити
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="font-size:0.75rem;color:var(--admin-text-light);font-style:italic">Основний</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>