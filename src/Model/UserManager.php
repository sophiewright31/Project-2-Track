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

        $statement->bindValue(':pseudo', $userData['pseudo']);
        $statement->bindValue(':password', $userData['password']);
        $statement->bindValue(':github', $userData['github']);

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

    //TODO CHECK IF THIS FUNCTION IS NECESSARY
    public function powerByUser($userID): array
    {
        $statement = $this->pdo->prepare("SELECT contribution_force FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $userID);
        $statement->execute();
        return $statement->fetch();
    }

    public function connect(): array
    {
        $query = 'SELECT u.id, u.pseudo, u.password, u.github, r.identifier FROM user as u
                JOIN role as r ON r.id = u.role_id';
        return $this->pdo->query($query)->fetchAll();
    }
}
