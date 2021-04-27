<?php

namespace App\Controller;

use App\Model\UserManager;

class ConnectController extends AbstractController
{
    public function add(): string
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
                $userManager->insert($userData);
                header('Location: /DJSet/index');
            }
        }

        return $this->twig->render('User/add.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function all(): string
    {
        $userManager = new UserManager();
        $userData = $userManager->selectAll('contribution_force', 'DESC');

        return $this->twig->render('User/all.html.twig', ['users' => $userData]);
    }
}
