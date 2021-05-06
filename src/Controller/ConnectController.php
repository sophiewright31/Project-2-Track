<?php

namespace App\Controller;

use App\Model\DJSetManager;
use App\Model\SongManager;
use App\Model\StyleManager;
use App\Model\UserManager;
use App\Service\Form\ConnectCheck;
use App\Service\Form\FormCheck;

class ConnectController extends AbstractController
{
    private const MAX_LENGTH_PSEUDO = 20;
    private const MAX_LENGTH_GITHUB = 20;
    private const MAX_LENGTH_PASSWORD = 255;


    public function formSignIn(): string
    {
        return $this->twig->render('User/form_user.html.twig');
    }

    public function signIn()
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
                    $id = $userManager->addUser($userData);
                    $_SESSION['pseudo'] = $userData['pseudo'];
                    $_SESSION['github'] = $userData['github'];
                    $_SESSION['role'] = 'contributor';
                    $_SESSION['id'] = intval($id);
                    header('location: /DJSet/index');
            }
            return $this->twig->render('User/form_user.html.twig', [
                'errors' => $errors,
            ]);
        }
    }

    public function all($id = null): string
    {
        $userManager = new UserManager();
        $userData = $userManager->showUsers();
        $songManager = new SongManager();
        $songs = $songManager->sortFighters();
        $rankings = [];

        foreach ($songs as $song) {
            if (empty($song['github'])) {
                $song['github'] = $song['pseudo'];
            }
            if (!array_key_exists($song['github'], $rankings)) {
                $rankings[$song['github']] = (int)$song['power'];
            } else {
                $rankings[$song['github']] += $song['power'];
            }
        }
        arsort($rankings);
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
            'fighters' => $rankings,
        ]);
    }

    public function connectUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $connectCheck = new ConnectCheck();
            $connectCheck->isPseudoExist($_POST['pseudo']);
            $connectCheck->isPasswordCorrect($_POST['pseudo'], $_POST['password']);
            $errors = $connectCheck->getErrors();
            if (empty($errors)) {
                $connectCheck->getSessionData($_POST['pseudo']);
                header('location: /DJSet/index');
                exit;
            } else {
                return $this->twig->render('djset/connect.html.twig', [
                    'errors' => $errors,
                ]);
            }
        }
    }

    public function disconnect(): void
    {
        session_destroy();
        unset($_SESSION);
        header('location: /');
    }
}
