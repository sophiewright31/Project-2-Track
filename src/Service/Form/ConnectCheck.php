<?php

namespace App\Service\Form;

use App\Model\UserManager;

class ConnectCheck extends FormCheck
{
    public function isPseudoExist(string $pseudo): bool
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        foreach ($users as $user) {
            if ($user['pseudo'] === $pseudo) {
                return true;
            }
        }
        $this->errors['isPseudoExist'] = "Le pseudo entré n'existe pas !";
        return false;
    }


    public function isPasswordCorrect(string $pseudo, string $password): bool
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        foreach ($users as $user) {
            if ($user['pseudo'] === $pseudo) {
                if (password_verify($password, $user['password'])) {
                    return true;
                }
                $this->errors['isPasswordCorrect'] = "Le mot de passe indiqué est faux !";
            }
        }
        return false;
    }

    public function getSessionData(string $pseudo): void
    {
        $userManager = new UserManager();
        $userData = $userManager->connect();
        foreach ($userData as $user) {
            if ($pseudo === $user['pseudo']) {
                    $_SESSION['pseudo'] = $user['pseudo'];
                    $_SESSION['github'] = $user['github'];
                    $_SESSION['role'] = $user['identifier'];
                    $_SESSION['id'] = $user['id'];
            }
        }
    }
}
