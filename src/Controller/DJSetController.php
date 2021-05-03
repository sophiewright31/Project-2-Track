<?php

namespace App\Controller;

use App\Model\DJSetManager;
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
            $styleManager = new StyleManager();
            $djSetManager = new DJSetManager();
            $styles = $styleManager->selectAll();
            $djStats = $djSetManager->selectStatsByUser($id);
            $djBadges = $djSetManager->selectBadgeByUser($id);
            $djSongs = $djSetManager->selectSongByUser($id);
            return $this->twig->render('djset/djhome.html.twig', [
                'djBadges' => $djBadges,
                'djStat' => $djStats,
                'styles' => $styles,
                'videos' => $djSongs
            ]);
        } else {
            return $this->twig->render('djset/connect.html.twig');
        }
    }

    public function addSong(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id'])) {
            $errors = [];
            $id = $_SESSION['id'];
            $djSetManager = new DJSetManager();

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
                $djBadges = $djSetManager->selectBadgeByUser($id);
                $djSongs = $djSetManager->selectSongByUser($id);
                return $this->twig->render('djset/djhome.html.twig', [
                    'djBadges' => $djBadges,
                    'styles' => $styles,
                    'badges' => $badges,
                    'djStats' => $djStats,
                    'videos' => $djSongs,
                ]);
            }
            $djBadges = $djSetManager->selectBadgeByUser($id);
            $djSongs = $djSetManager->selectSongByUser($id);
            //TODO modifier le chemin pour affichage des erreurs
            return $this->twig->render('djset/djhome.html.twig', [
                'djBadges' => $djBadges,
                'errors' => $errors,
                'styles' => $styles,
                'djStats' => $djStats,
                'videos' => $djSongs,
            ]);
        }
        //If don't come from a post go to error 405
        header("HTTP/1.0 405 Method Not Allowed");
        return (new ErrorHandleController())->badMethod();
    }
}
