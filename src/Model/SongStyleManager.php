<?php

namespace App\Model;

class SongStyleManager extends AbstractManager
{
    public const TABLE = 'song_style';

    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {

        $query = 'SELECT ss.song_id, s.name
                  FROM ' . static::TABLE . ' ss' . '
                  JOIN style s ON s.id = ss.style_id
                  ORDER BY ' . $orderBy . ' ' . $direction;

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectByStyle($style): array
    {
        $thisMonth = date("Y-m");
        $statement = $this->pdo->prepare('SELECT s.id, s.youtube_id, s.power, u.github, st.name as style_name
                  FROM ' . static::TABLE . ' ss' . '
                  JOIN song s ON s.id = ss.song_id
                  JOIN style st on st.id = ss.style_id
                  JOIN user u on u.id = s.user_id
                  WHERE st.identifier = :varstyle and DATE_FORMAT(s.created_at, "%Y-%m") = "' . $thisMonth . '"');
        $statement->bindValue('varstyle', $style);
        $statement->execute();
        return $statement->fetchAll();
    }
}
