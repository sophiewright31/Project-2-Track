<?php


namespace App\Controller;


use App\Model\AdminManager;

class AdminController extends AbstractController
{
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminManager = new AdminManager();
            $adminManager->delete($id);
            header('Location: /');
        }
    }
}