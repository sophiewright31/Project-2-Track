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
        $statement = $this->pdo->prepare('SELECT s.id, s.youtube_id, st.name as style_name
                  FROM ' . static::TABLE . ' ss' . '
                  JOIN song s ON s.id = ss.song_id
                  JOIN style st on st.id = ss.style_id
                  WHERE st.identifier = :varstyle');
        $statement->bindValue('varstyle', $style);
        $statement->execute();
        return $statement->fetchAll();
    }
}
