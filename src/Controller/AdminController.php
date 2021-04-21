<?php

namespace App\Controller;

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
}
