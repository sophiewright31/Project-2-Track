<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
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
                $users = array_map('trim', $_POST);
                $userManager->insert($users);
                header('Location: /');
            }
        }

        return $this->twig->render('User/add.html.twig', [
            'errors' => $errors,
        ]);
    }
}
