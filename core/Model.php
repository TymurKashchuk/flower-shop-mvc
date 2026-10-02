<?php

namespace core;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function all(string $orderBy = 'id', string $dir = 'DESC'): array
    {
        $orderBy = preg_match('/^[a-zA-Z0-9_]+$/', $orderBy) ? $orderBy : 'id';
        $dir  = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$dir}");
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }

    public function lastId() :string
    {
        return $this->db->lastInsertId();
    }
}