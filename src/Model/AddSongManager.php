<?php
namespace App\Model;

class AddSongManager extends AbstractManager
{
    public const TABLE = 'song';

    public function insert($songData)
    {

        $query = 'INSERT INTO song (user_id, title, youtube_id, created_at) VALUES (:user_id, :title, :youtube_id, NOW())';
        $statement = $this->pdo->prepare($query);
        //TODO user_id hardcodé
        $statement->bindValue('user_id', 4,\PDO::PARAM_STR);
        $statement->bindValue('title', $songData['title'],\PDO::PARAM_STR);
        $statement->bindValue('youtube_id', $songData['youtube_id'],\PDO::PARAM_STR);
        $statement->execute();
        return(int)$this->pdo->lastInsertId();
    }
}