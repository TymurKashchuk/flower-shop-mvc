<?php

namespace app\Models;

use core\Model;

class News extends Model
{
    protected string $table = 'news';

    public function getPaginated(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE is_active = 1
            ORDER BY published_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll();
    }

    public function countPublished(): int
    {
        return (int)$this->db->query("
            SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1
        ")->fetchColumn();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE slug = ? AND is_active = 1 LIMIT 1
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function allForAdmin(): array
    {
        return $this->db->query("
            SELECT * FROM {$this->table} ORDER BY published_at DESC
        ")->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (title, slug, content, image, is_active)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? null,
            $data['is_active'] ?? 0,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET title=?, slug=?, content=?, image=?, is_active=?
            WHERE id=?
        ");
        return $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? null,
            $data['is_active'] ?? 0,
            $id,
        ]);
    }

    public function getFiltered(int $page, int $perPage, ?string $month = null): array
    {
        $offset = ($page - 1) * $perPage;
        $where = 'WHERE is_active = 1';
        $params = [];

        if ($month) {
            $where .= ' AND DATE_FORMAT(published_at, "%Y-%m") = ?';
            $params[] = $month;
        }

        $stmt = $this->db->prepare("
        SELECT * FROM {$this->table}
        {$where}
        ORDER BY published_at DESC
        LIMIT ? OFFSET ?
    ");
        $params[] = $perPage;
        $params[] = $offset;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countFiltered(?string $month = null): int
    {
        $where = 'WHERE is_active = 1';
        $params = [];

        if ($month) {
            $where .= ' AND DATE_FORMAT(published_at, "%Y-%m") = ?';
            $params[] = $month;
        }

        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM {$this->table} {$where}
    ");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getAvailableMonths(): array
    {
        return $this->db->query("
        SELECT DISTINCT DATE_FORMAT(published_at, '%Y-%m') as month
        FROM {$this->table}
        WHERE is_active = 1
        ORDER BY month DESC
        LIMIT 3
    ")->fetchAll(\PDO::FETCH_COLUMN);
    }
}