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

    public function insert($songData): void
    {
        $query = 'INSERT INTO song (user_id, youtube_id, created_at, updated_at, power)
                  VALUES (:user_id, :youtube_id, NOW(), NOW(), 0)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('user_id', $songData['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('youtube_id', $songData['youtube_id']);
        $statement->execute();
        $id = $this->pdo->lastInsertId();
        if (!empty($songData['style'])) {
            foreach ($songData['style'] as $style) {
                $query = 'INSERT INTO song_style (style_id, song_id)
                      VALUES (:styleid, :songid)';
                $statement = $this->pdo->prepare($query);
                $statement->bindValue(':styleid', $style, \PDO::PARAM_INT);
                $statement->bindValue(':songid', $id, \PDO::PARAM_INT);
                $statement->execute();
            }
        }
    }

    public function updatePowerById($id): void
    {
        $query = 'UPDATE song
                  SET power = power+1,
                      updated_at = NOW() 
                  WHERE id=:id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectAllTopSong(string $orderBy = '', string $direction = 'DESC'): array
    {
        $query = 'SELECT youtube_id, power, user.pseudo, user.github, song.id 
                  FROM ' . self::TABLE . '
                  JOIN user ON song.user_id = user.id
                  ORDER BY ' . $orderBy . ' ' . $direction . ' LIMIT 3';

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT s.id, s.youtube_id, s.power, s.created_at, s.updated_at, u.github  
                  FROM song s
                  JOIN user u on u.id = s.user_id';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        return $this->pdo->query($query)->fetchAll();
    }


    //TODO /!\ ATTENTION PEUT ETRE REDONDANT AVEC LE STATISTIQUE DE MATTHIEU
    public function songPostedByUser($userId): array
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as countSongs FROM " . self::TABLE . " WHERE user_id = :id");
        $statement->bindValue(':id', $userId);
        $statement->execute();
        return $statement->fetch();
    }
}
