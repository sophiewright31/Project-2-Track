<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function insert($userData)
    {
        //TODO role_id est hard codé : role de contributeur(2) par défaut
        $query = 'INSERT INTO user (pseudo, role_id, password, github, created_at, updated_at)
                  VALUES (:pseudo, 2, :password, :github, NOW(), NOW())';
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':pseudo', $userData['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $userData['password'], \PDO::PARAM_STR);
        $statement->bindValue(':github', $userData['github'], \PDO::PARAM_STR);

        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    public function powerUpById($userID): void
    {
        $query = 'UPDATE user
        SET contribution_force = contribution_force + 1
        WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $userID, \PDO::PARAM_INT);
        $statement->execute();
    }


    public function powerByUser($userID): array
    {
        $statement = $this->pdo->prepare("SELECT contribution_force FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $userID);
        $statement->execute();
        return $statement->fetch();
    }
}
