<?php

namespace App\Controller;

use App\Model\DJSetManager;
use App\Model\StyleManager;

class DJSetController extends AbstractController
{
    public function index(): string
    {
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $djSetManager = new DJSetManager();
            $styleManager = new StyleManager();
            $styles = $styleManager->selectAll();
            $djStats = $djSetManager->selectStatsContributor($id);
            return $this->twig->render('djset/djhome.html.twig', [
                'djStat' => $djStats,
                'styles' => $styles,
                'user_id' => $_SESSION['id']
            ]);
        } else {
            return $this->twig->render('djset/connect.html.twig');
        }
    }
}
