<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function insert($userData)
    {
        //role_id est hard codé : role de contributeur(2) par défaut
        $query = 'INSERT INTO user (pseudo, role_id, password, github, created_at, updated_at)
                  VALUES (:pseudo, 2, :password, :github, NOW(), NOW())';
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':pseudo', $userData['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $userData['password'], \PDO::PARAM_STR);
        $statement->bindValue(':github', $userData['github'], \PDO::PARAM_STR);

        $statement->execute();
        return $this->pdo->lastInsertId();
    }
}
