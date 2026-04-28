<?php

namespace app\Models;

use core\Model;

class Category extends Model
{
    protected string $table = 'categories';

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function getAllForMenu(): array
    {
        return $this->db->query("SELECT id, name, slug FROM {$this->table} ORDER BY name ASC")->fetchAll();
    }
}