<?php

namespace App\Controller;

use App\Model\SongManager;
use App\Model\StyleManager;
use App\Model\UserManager;
use App\Service\Badge\GamificationCalculator;

class DJSetController extends AbstractController
{
    public function index(): string
    {
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $userManager = new UserManager();
            $styleManager = new StyleManager();
            $styles = $styleManager->selectAll();
            $djStats = $userManager->selectStatsContributor($id);
            return $this->twig->render('djset/djhome.html.twig', [
                'djStats' => $djStats,
                'styles' => $styles,
            ]);
        } else {
            return $this->twig->render('djset/connect.html.twig');
        }
    }

    public function addSong(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id'])) {
            $errors = [];
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
            $id = $_SESSION['id'];
            $_POST['user_id'] = $id;
            $styleManager = new StyleManager();
            $userManager = new UserManager();
            $djStats = $userManager->selectStatsContributor($id);
            $styles = $styleManager->selectAll();


            if (empty($errors)) {
                $songManager = new SongManager();
                $gamificationService = new GamificationCalculator();
                $songData = $_POST;
                $songData['user_id'] = $id;
                $songManager->insert($songData);

                $badges = [];
                $userId = $_SESSION['id'];
                $songPosted = $songManager->songPostedByUser($userId);
                $badges[] = $gamificationService->badgeSongs($songPosted['countSongs'], $userId);

                return $this->twig->render('djset/djhome.html.twig', [
                    'styles' => $styles,
                    'badges' => $badges,
                    'djStats' => $djStats,
                ]);
            }
            //TODO modifier le chemin pour affichage des erreurs
            return $this->twig->render('djset/djhome.html.twig', [
                'errors' => $errors,
                'styles' => $styles,
                'djStats' => $djStats,
            ]);
        }
        //If don't come from a post go to error 405
        header("HTTP/1.0 405 Method Not Allowed");
        return (new ErrorHandleController())->badMethod();
    }
}
