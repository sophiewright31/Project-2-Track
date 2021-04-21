<?php

namespace App\Model;

class SongManager extends AbstractManager
{
    public const TABLE = 'song';

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . static::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

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

    public function updatePowerById($id): void
    {
        $statement = $this->pdo->prepare('UPDATE song SET power = power+1 WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
