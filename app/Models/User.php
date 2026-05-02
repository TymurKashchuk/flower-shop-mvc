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
        return $stmt->execute([$data['name'],$data['email'],password_hash($data['password'], PASSWORD_BCRYPT),]);
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return (int)$stmt->fetchColumn() > 0;
    }

}