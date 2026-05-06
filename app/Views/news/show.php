<?php /** @var array $item */ ?>

<nav class="breadcrumb">
    <a href="<?= BASE_URL ?>">Головна</a>
    <span class="breadcrumb__sep">/</span>
    <a href="<?= BASE_URL ?>/news">Новини</a>
    <span class="breadcrumb__sep">/</span>
    <span><?= htmlspecialchars($item['title']) ?></span>
</nav>

<article class="news-article">
    <header class="news-article__header">
        <time class="news-article__date">
            <?= !empty($item['published_at']) ? date('d F Y', strtotime($item['published_at'])) : '' ?>
        </time>
        <h1 class="news-article__title">
            <?= htmlspecialchars($item['title']) ?>
        </h1>
    </header>

    <?php if (!empty($item['image'])): ?>
        <div class="news-article__cover">
            <img src="<?= htmlspecialchars($item['image']) ?>"
                 alt="<?= htmlspecialchars($item['title']) ?>"
                 loading="lazy">
        </div>
    <?php endif; ?>

    <div class="news-article__body">
        <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
    </div>

    <footer class="news-article__footer">
        <a href="<?= BASE_URL ?>/news" class="btn btn-ghost">
            ← Повернутись до новин
        </a>
    </footer>
</article>