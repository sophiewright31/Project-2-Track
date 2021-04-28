<?php

namespace App\Controller;

use App\Model\DJSetManager;
use App\Model\SongManager;
use App\Model\StyleManager;

class DJSetController extends AbstractController
{
    public function index(): string
    {
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $djSetManagerStats = new DJSetManager();
            $styleManager = new StyleManager();
            $djSetManagerBadge = new DJSetManager();
            $djSongManager = new DJSetManager();
            $styles = $styleManager->selectAll();
            $djStats = $djSetManagerStats->selectStatsByUser($id);
            $djBadges = $djSetManagerBadge->selectBadgeByUser($id);
            $djSongs = $djSongManager->selectSongByUser($id);
            return $this->twig->render('djset/djhome.html.twig', [
                'djBadges' => $djBadges,
                'djStat' => $djStats,
                'styles' => $styles,
                'user_id' => $_SESSION['id'],
                'djSongs' => $djSongs
            ]);
        } else {
            return $this->twig->render('djset/connect.html.twig');
        }
    }
}
