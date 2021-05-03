<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public const ROLE = 2;

    public function addUser($userData)
    {
        $query = 'INSERT INTO user (pseudo, role_id, password, github, created_at, updated_at, contribution_force)
                  VALUES (:pseudo, ' . self::ROLE . ' , :password, :github, NOW(), NOW(), 0)';
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':pseudo', $userData['pseudo']);
        $statement->bindValue(':password', $userData['password']);
        $statement->bindValue(':github', $userData['github']);

        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    public function showUsers()
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' ORDER BY contribution_force LIMIT 10';

        return $this->pdo->query($query)->fetchAll();
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

    public function selectStatsContributor(int $id)
    {
        $query = 'SELECT u.contribution_force, u.github, u.pseudo, count(s.youtube_id)
                FROM user u
                LEFT JOIN song s ON u.id = s.user_id
                WHERE u.id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function showNbUser()
    {
        $query = 'SELECT count(pseudo) as count FROM ' . self::TABLE;

        return $this->pdo->query($query)->fetch();
    }

    public function showNbUserByMonth()
    {
        $thisMonth = date("Y-m");
        $query = 'SELECT count(pseudo) as count FROM ' . self::TABLE . '
                WHERE DATE_FORMAT(created_at, "%Y-%m") = "' . $thisMonth . '"';

        return $this->pdo->query($query)->fetch();
    }
}
