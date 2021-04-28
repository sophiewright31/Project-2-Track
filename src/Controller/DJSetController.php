<?php

namespace App\Controller;

use App\Model\SongManager;
use App\Model\DJSetManager;
use App\Model\StyleManager;
use App\Service\Badge\GamificationCalculator;

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

    public function addSong(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['user_id'])) {
                $errors['user_id'] = 'Please enter your id';
            }
            if (empty($_POST['youtube_id'])) {
                $errors['youtube_id'] = 'Please enter your youtube URL';
            }
            $urlString = parse_url($_POST['youtube_id'], PHP_URL_QUERY);
            $args = [];
            parse_str($urlString, $args);
            if (isset($args['v'])) {
                $_POST['youtube_id'] = $args['v'];
            } else {
                $errors['youtube_id'] = 'Please enter a valid youtube URL';
            }

            if (empty($errors)) {
                $styleManager = new StyleManager();
                $songManager = new SongManager();
                $gamificationService = new GamificationCalculator();
                $songManager->insert($_POST);
                //TODO ICI ON PLACE LA GAMIFICATION
                //TODO 1- Récupère le nb chanson posté par le user
                $userId = $_POST['user_id'];
                $songPosted = $songManager->songPostedByUser($userId);
                $badgeName = $gamificationService->badgeSongs($songPosted['countSongs'], $userId);

                $styles = $styleManager->selectAll();

                return $this->twig->render('djset/djhome.html.twig', [
                    'styles' => $styles,
                    'badgeName' => $badgeName,
                ]);
            }
        }
        //TODO modifier le chemin pour affichage des erreurs
        return $this->twig->render('djset/djhome.html.twig', [
            'errors' => $errors,
        ]);
    }
}
