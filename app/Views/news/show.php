<?php /** @var array $item */ ?>

<nav class="breadcrumb">
    <a href="<?= BASE_URL ?>">Головна</a>
    <span class="breadcrumb__sep">/</span>
    <a href="<?= BASE_URL ?>/news">Новини</a>
    <span class="breadcrumb__sep">/</span>
    <span><?= htmlspecialchars($item['title']) ?></span>
</nav>

<article style="max-width: 720px; margin-inline: auto;">
    <p style="font-size:var(--text-sm);color:var(--color-text-muted);margin-bottom:var(--space-4)">
        <?= !empty($item['published_at']) ? date('d.m.Y', strtotime($item['published_at'])) : '' ?>
    </p>
    <h1 style="font-family:var(--font-display);font-size:var(--text-xl);font-weight:600;margin-bottom:var(--space-6)">
        <?= htmlspecialchars($item['title']) ?>
    </h1>
    <?php if (!empty($item['image'])): ?>
        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>"
             style="width:100%;border-radius:var(--radius-lg);margin-bottom:var(--space-8)" loading="lazy">
    <?php endif; ?>
    <div style="font-size:var(--text-base);line-height:1.8;color:var(--color-text)">
        <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
    </div>
</article>
