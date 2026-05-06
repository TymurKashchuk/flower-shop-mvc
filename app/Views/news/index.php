<?php /** @var array $items */
/** @var int $page */
/** @var int $pages */
/** @var array $months */
/** @var string|null $month */ ?>

    <div class="news-header">
        <h1 class="news-header__title">Новини</h1>
        <p class="news-header__subtitle">Акції, події та оновлення нашого салону</p>
    </div>

<?php if (!empty($months)): ?>
    <div class="news-filters">
        <a href="<?= BASE_URL ?>/news"
           class="filter-chip <?= !$month ? 'filter-chip--active' : '' ?>">
            Всі
        </a>
        <?php foreach ($months as $m): ?>
            <?php
            [$y, $mo] = explode('-', $m);
            $monthNames = ['01' => 'Січень', '02' => 'Лютий', '03' => 'Березень', '04' => 'Квітень',
                    '05' => 'Травень', '06' => 'Червень', '07' => 'Липень', '08' => 'Серпень',
                    '09' => 'Вересень', '10' => 'Жовтень', '11' => 'Листопад', '12' => 'Грудень'];
            $label = ($monthNames[$mo] ?? $mo) . ' ' . $y;
            ?>
            <a href="?month=<?= htmlspecialchars($m) ?>"
               class="filter-chip <?= $month === $m ? 'filter-chip--active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (empty($items)): ?>
    <div class="empty-state">
        <div class="empty-state__icon">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM3 9h18"
                      stroke="currentColor" stroke-width="1.5"/>
            </svg>
        </div>
        <h2 class="empty-state__title">Новин поки немає</h2>
        <p class="empty-state__desc">
            <?= $month ? 'За цей місяць новин не знайдено' : 'Заходьте пізніше' ?>
        </p>
        <?php if ($month): ?>
            <a href="<?= BASE_URL ?>/news" class="btn btn-ghost">Показати всі</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="news-grid">
        <?php foreach ($items as $item): ?>
            <a href="<?= BASE_URL ?>/news/<?= htmlspecialchars($item['slug']) ?>" class="news-card">
                <div class="news-card__image">
                    <?php if (!empty($item['image'])): ?>
                        <img src="<?= htmlspecialchars($item['image']) ?>"
                             alt="<?= htmlspecialchars($item['title']) ?>"
                             loading="lazy">
                    <?php else: ?>
                        <div class="news-card__no-image">
                            <svg viewBox="0 0 24 24" fill="none" width="40" height="40">
                                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM3 9h18"
                                      stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="news-card__body">
                    <time class="news-card__date">
                        <?= $item['published_at'] ? date('d.m.Y', strtotime($item['published_at'])) : '' ?>
                    </time>
                    <h2 class="news-card__title">
                        <?= htmlspecialchars($item['title']) ?>
                    </h2>
                    <p class="news-card__excerpt">
                        <?= htmlspecialchars(mb_substr($item['content'] ?? '', 0, 110)) ?>…
                    </p>
                    <span class="news-card__more">Читати далі →</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Сторінки">
            <?php if ($page > 1): ?>
                <a href="?<?= http_build_query(['page' => $page - 1, 'month' => $month]) ?>"
                   class="pagination__btn" aria-label="Попередня">‹</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?<?= http_build_query(['page' => $i, 'month' => $month]) ?>"
                   class="pagination__btn <?= $i === $page ? 'pagination__btn--active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($page < $pages): ?>
                <a href="?<?= http_build_query(['page' => $page + 1, 'month' => $month]) ?>"
                   class="pagination__btn" aria-label="Наступна">›</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>