<?php

namespace App\Model;

class UserBadgeManager extends AbstractManager
{
    public const TABLE = 'user_badge';

    public function insert(array $post): string
    {
        $query = 'INSERT INTO user_badge (user_id, badge_id) VALUES (:user_id, :badge_id)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':user_id', $post['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':badge_id', $post['badge_id'], \PDO::PARAM_INT);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    public function attributeByBadgeName(string $badgeName, int $userID): void
    {
        $query = 'INSERT INTO user_badge (user_id, badge_id) VALUES
        (:userID, (SELECT b.id FROM badge b WHERE b.name = :badgeName))';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userID', $userID, \PDO::PARAM_INT);
        $statement->bindValue(':badgeName', $badgeName);
        $statement->execute();
    }
}
