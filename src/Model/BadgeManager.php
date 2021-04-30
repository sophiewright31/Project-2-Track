<?php

namespace App\Model;

class BadgeManager extends AbstractManager
{
    public const TABLE = 'badge';

    public function insert($badgeData): int
    {
        $query = 'INSERT INTO badge (name, picture_url, description, created_at, updated_at)
                VALUES (:name, :picture_url, :description, NOW(), NOW())';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $badgeData['name'], \PDO::PARAM_STR);
        $statement->bindValue('picture_url', $badgeData['picture_url'], \PDO::PARAM_STR);
        $statement->bindValue('description', $badgeData['description'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . static::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function idByName(string $badgeName): array
    {
        $statement = $this->pdo->prepare("SELECT id FROM " . self::TABLE . " WHERE name = :badgeName");
        $statement->bindValue(':badgeName', $badgeName);
        $statement->execute();
        return $statement->fetch();
    }
    public function showNbBadge()
    {
        $query = 'SELECT count(id) as count FROM ' . self::TABLE;
        return $this->pdo->query($query)->fetch();
    }
}
