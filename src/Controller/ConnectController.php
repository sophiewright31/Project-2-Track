<?php

namespace App\Controller;

use App\Model\SongManager;
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
            $formCheck->emptyField('pseudo', $_POST['pseudo']);
            $formCheck->emptyField('password', $_POST['password']);
            $formCheck->isPseudoUsed($_POST['pseudo']);
            $formCheck->isLengthRespected(self::MAX_LENGTH_PSEUDO, $_POST['pseudo'], 'pseudo');
            $formCheck->isLengthRespected(self::MAX_LENGTH_GITHUB, $_POST['github'], 'github');
            $formCheck->isLengthRespected(self::MAX_LENGTH_PASSWORD, $_POST['password'], 'password');
            $errors = $formCheck->getErrors();


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

    public function all($id = null): string
    {
        $userManager = new UserManager();
        $userData = $userManager->showUsers();
        $songManager = new SongManager();
        $classement = $songManager->sortFighters();
        $totalPower = $songManager->countTotalPower();
        $totalPowerId = 0;
        if ($id !== null) {
            $songManager = new SongManager();
            $totalPowerId = $songManager->countTotalPowerById($id);
        }

        return $this->twig->render('User/all.html.twig', [
            'users' => $userData,
            'totalPower' => $totalPower,
            'totalPowerById' => $totalPowerId,
            'fighters' => $classement,
            ]);
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
