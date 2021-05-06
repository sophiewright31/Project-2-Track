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
        $thisMonth = date("Y-m");
        $query = 'SELECT youtube_id, power, user.pseudo, user.github, song.id 
                  FROM ' . self::TABLE . '
                  JOIN user ON song.user_id = user.id
                  WHERE DATE_FORMAT(song.created_at, "%Y-%m") = "' . $thisMonth . '"
                  ORDER BY ' . $orderBy . ' ' . $direction . ' LIMIT 3';

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectAll(string $orderBy = 'power', string $direction = 'DESC'): array
    {
        $thisMonth = date("Y-m");
        $query = 'SELECT s.id, s.youtube_id, s.power, s.created_at, s.updated_at, u.github  
                  FROM song s
                  JOIN user u on u.id = s.user_id
                  WHERE DATE_FORMAT(s.created_at, "%Y-%m") = "' . $thisMonth . '"';
                  ;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        return $this->pdo->query($query)->fetchAll();
    }

    public function selectAllSong(string $orderBy = 'power', string $direction = 'DESC'): array
    {
        $query = 'SELECT s.id, s.youtube_id, s.power, s.created_at, s.updated_at, u.github  
                  FROM song s
                  JOIN user u on u.id = s.user_id';
        ;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        return $this->pdo->query($query)->fetchAll();
    }

    public function countTotalPower(): array
    {
        $query = 'SELECT sum(power) as total FROM song';
        return $this->pdo->query($query)->fetch();
    }

    public function countTotalPowerById($id): array
    {

        $query = 'SELECT sum(power) as total FROM ' . static::TABLE . ' WHERE user_id=' . $id;
        return $this->pdo->query($query)->fetch();
    }

    //TODO /!\ ATTENTION PEUT ETRE REDONDANT AVEC LE STATISTIQUE DE MATTHIEU
    public function songPostedByUser($userId): array
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as countSongs FROM " . self::TABLE . " WHERE user_id = :id");
        $statement->bindValue(':id', $userId);
        $statement->execute();
        return $statement->fetch();
    }

    public function showNbSong()
    {
        $query = 'SELECT COUNT(youtube_id) as count FROM ' . self::TABLE;
        return $this->pdo->query($query)->fetch();
    }

    public function showNbSongsByMonth()
    {
        $thisMonth = date("Y-m");
        $query = 'SELECT count(youtube_id) as count FROM ' . self::TABLE . '
                WHERE DATE_FORMAT(created_at, "%Y-%m") = "' . $thisMonth . '"';
        return $this->pdo->query($query)->fetch();
    }

    public function showNbSongsByDay()
    {
        $today = date("Y-m-d");
        $query = 'SELECT count(youtube_id) as count FROM ' . self::TABLE . '
        WHERE DATE_FORMAT(created_at, "%Y-%m-%d") = "' . $today . '"';
        return $this->pdo->query($query)->fetch();
    }

    public function sortFighters()
    {
        $query =    'SELECT user.github, user.pseudo, song.power, song.user_id FROM ' . self::TABLE . '
                    JOIN user ON song.user_id = user.id';
        return $this->pdo->query($query)->fetchAll();
    }
}
