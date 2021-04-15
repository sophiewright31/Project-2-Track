<?php

namespace App\Model;

class ForceManager extends AbstractManager
{
    public const TABLE = 'song';

    public function songPower()
    {
        $query = 'SELECT id, power FROM song ORDER BY id';
        $statement = $this->pdo->query($query);
        return $statement->fetchAll();
    }

    public function updatePowerById($id): void
    {
        $statement = $this->pdo->prepare('UPDATE song SET power = power+1 WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
