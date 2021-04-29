<?php

namespace App\Model;

class DJSetManager extends AbstractManager
{
    public const TABLE = 'user';

    public function selectStatsByUser(int $id)
    {
        $query = 'SELECT u.contribution_force, u.github, u.pseudo, count(s.youtube_id) as countSong
                FROM user as u
                LEFT JOIN song as s ON u.id = s.user_id
                WHERE u.id = :id
                GROUP BY u.id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function selectBadgeByUser(int $id)
    {
        $query = 'SELECT u.id, b.name, b.picture_url, b.description 
                FROM badge b
                JOIN user_badge ub ON b.id = ub.badge_id
                JOIN user u ON u.id = ub.user_id
                WHERE u.id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function selectSongByUser($id): array
    {
        $query = 'SELECT s.id, s.youtube_id, s.power, s.created_at, s.updated_at, u.github  
                  FROM song s
                  JOIN user u on u.id = s.user_id
                  WHERE u.id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetchAll();
    }
}
