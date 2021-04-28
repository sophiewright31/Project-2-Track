<?php

namespace App\Controller;

use App\Model\UserManager;

class ConnectController extends AbstractController
{
    public function sign(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['pseudo'])) {
                $errors['pseudo'] = 'Veuillez renseigner un pseudo';
            }
            if (empty($_POST['password'])) {
                $errors['password'] = 'Veuillez renseigner un mot de passe';
            }
            if (empty($errors)) {
                $userManager = new UserManager();
                $userData = array_map('trim', $_POST);
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                $userManager->insert($userData);
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
