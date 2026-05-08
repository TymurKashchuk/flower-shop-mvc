<?php /** @var array $items */ ?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
        <tr>
            <th>Імʼя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Статус</th>
            <th>Дата реєстрації</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($items)): ?>
            <tr>
                <td colspan="6"
                    style="text-align:center;color:var(--color-text-muted);padding:var(--space-8)">
                    Користувачів немає
                </td>
            </tr>
        <?php endif; ?>
        <?php foreach ($items as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td style="color:var(--color-text-muted)"><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <?php if ($u['role'] === 'admin'): ?>
                        <span class="admin-badge admin-badge--success">Адмін</span>
                    <?php else: ?>
                        <span class="admin-badge">Користувач</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($u['is_banned']): ?>
                        <span class="admin-badge admin-badge--error">Заблоковано</span>
                    <?php else: ?>
                        <span class="admin-badge admin-badge--success">Активний</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap;color:var(--color-text-muted)">
                    <?= date('d.m.Y', strtotime($u['created_at'])) ?>
                </td>
                <td>
                    <?php if ($u['role'] !== 'admin'): ?>
                        <div style="display:flex;gap:var(--space-2)">
                            <?php if ($u['is_banned']): ?>
                                <form method="POST"
                                      action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/unban">
                                    <button class="btn btn-primary"
                                            style="padding:4px 12px;font-size:var(--text-sm)">
                                        Розблокувати
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST"
                                      action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/ban"
                                      onsubmit="return confirm('Заблокувати користувача?')">
                                    <button class="btn"
                                            style="padding:4px 12px;font-size:var(--text-sm);
                                                   color:var(--color-error);border-color:var(--color-error)">
                                        Заблокувати
                                    </button>
                                </form>
                            <?php endif; ?>

                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/delete"
                                  onsubmit="return confirm('Видалити користувача <?= htmlspecialchars($u['name'], ENT_QUOTES) ?>? Це незворотно.')">
                                <button class="btn"
                                        style="padding:4px 12px;font-size:var(--text-sm);
                                               color:var(--color-error);border-color:var(--color-error)">
                                    Видалити
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>