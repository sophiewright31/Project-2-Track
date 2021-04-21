<?php

namespace App\Model;

class AddSongManager extends AbstractManager
{
    public const TABLE = 'song';

    public function insert($songData): int
    {
        $query = 'INSERT INTO song (user_id, youtube_id, created_at, updated_at, power)
                  VALUES (:user_id, :youtube_id, NOW(), NOW(), 0)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('user_id', $songData['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('youtube_id', $songData['youtube_id']);
        $statement->execute();
        return(int)$this->pdo->lastInsertId();
    }
}
