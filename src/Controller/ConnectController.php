<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\Form\FormCheck;

class ConnectController extends AbstractController
{
    private const MAX_LENGTH_PSEUDO = 100;
    private const MAX_LENGTH_GITHUB = 100;
    private const MAX_LENGTH_PASSWORD = 255;


    public function signIn(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formCheck = new FormCheck();
            $_POST = $formCheck->cleanPost($_POST);
            $errors['pseudo'] = $formCheck->emptyField('Pseudo', $_POST['pseudo']);
            $errors['password'] = $formCheck->emptyField('Password', $_POST['password']);
            $errors['pseudoAlreadyUsed'] = $formCheck->isPseudoUsed($_POST['pseudo']);
            $errors['pseudoLength'] = $formCheck->isLengthRespected(self::MAX_LENGTH_PSEUDO, $_POST['pseudo']);
            $errors['githubLength'] = $formCheck->isLengthRespected(self::MAX_LENGTH_GITHUB, $_POST['github']);
            $errors['passwordLength'] = $formCheck->isLengthRespected(self::MAX_LENGTH_PASSWORD, $_POST['password']);

            $errors = $formCheck->cleanError($errors);

            if (empty($errors)) {
                $userManager = new UserManager();
                $userData = array_map('trim', $_POST);
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                $userManager->addUser($userData);
                header('Location: /DJSet/index');
            }
        }

        return $this->twig->render('User/form_user.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function all(): string
    {
        $userManager = new UserManager();
        $userData = $userManager->selectAll('contribution_force', 'DESC');

        return $this->twig->render('User/all.html.twig', ['users' => $userData]);
    }

    public function connectUser(): void
    {
        $userManager = new UserManager();
        $userData = $userManager->connect();
        foreach ($userData as $user) {
            if ($_POST['pseudo'] === $user['pseudo']) {
                if (password_verify($_POST['password'], $user['password'])) {
                        $_SESSION['pseudo'] = $user['pseudo'];
                        $_SESSION['github'] = $user['github'];
                        $_SESSION['role'] = $user['identifier'];
                        $_SESSION['id'] = $user['id'];
                }
            }
        }
        header('Location: /');
    }

    public function disconnect(): void
    {
        session_destroy();
        unset($_SESSION);
        header('location: /');
    }
}
