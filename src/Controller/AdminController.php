<?php

namespace App\Controller;

use App\Model\SongManager;
use App\Model\BadgeManager;

class AdminController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('admin/admin.html.twig');
    }

    public function showAllBadges(): string
    {
        $badgeManager = new BadgeManager();
        $badges = $badgeManager->selectAll();
        return $this->twig->render('admin/badges.html.twig', [
            'badges' => $badges
        ]);
    }

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminManager = new SongManager();
            $adminManager->delete($id);
            header('Location: /');
        }
    }
}
