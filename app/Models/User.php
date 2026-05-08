<?php

namespace app\Models;

use core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name,email,password_hash,role) VALUES (?,?,?, 'user')");
        return $stmt->execute([$data['name'], $data['email'], password_hash($data['password'], PASSWORD_BCRYPT),]);
    }

    public function emailExists(string $email, int $excludeId = 0): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE email = ? AND id != ?");
        $stmt->execute([$email, $excludeId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function updateProfile(int $id, array $data): bool
    {
        $fields = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET $fields WHERE id = ?"
        );
        return $stmt->execute([...array_values($data), $id]);
    }

    public function allForAdmin(): array
    {
        $stmt = $this->db->query("
            SELECT id, name, email, role, is_banned, created_at
            FROM {$this->table}
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function ban(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET is_banned = 1 WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public function unban(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET is_banned = 0 WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}