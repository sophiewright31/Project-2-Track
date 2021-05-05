<?php

namespace App\Controller;

use App\Model\DJSetManager;
use App\Model\SongManager;
use App\Model\StyleManager;
use App\Model\UserManager;
use App\Service\Badge\GamificationCalculator;
use App\Service\Form\VideoCheck;

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
            $videoCheck = new VideoCheck();
            $videoId = $videoCheck->extractYoutubeId($_POST['youtube_id']);
            $videoCheck->videoExistThisMonth($videoId);
            $errors = $videoCheck->getErrors();
            $_POST = $videoCheck->modifyPost($_POST, $videoId);

            $djSetManager = new DJSetManager();
            $styleManager = new StyleManager();
            $userManager = new UserManager();
            $djStats = $userManager->selectStatsContributor($_POST['user_id']);
            $styles = $styleManager->selectAll();


            if (empty($errors)) {
                    $songManager = new SongManager();
                    $gamificationService = new GamificationCalculator();
                    $songData = $_POST;
                    $songData['user_id'] = $_POST['user_id'];
                    $songManager->insert($songData);

                    $badges = [];
                    $songPosted = $songManager->songPostedByUser($_POST['user_id']);
                    $badges[] = $gamificationService->badgeSongs($songPosted['countSongs'], $_POST['user_id']);
                    $djBadges = $djSetManager->selectBadgeByUser($_POST['user_id']);
                    $djSongs = $djSetManager->selectSongByUser($_POST['user_id']);
                    return $this->twig->render('djset/djhome.html.twig', [
                        'djBadges' => $djBadges,
                        'styles' => $styles,
                        'badges' => $badges,
                        'djStats' => $djStats,
                        'videos' => $djSongs,
                        ]);
            }
            $djBadges = $djSetManager->selectBadgeByUser($_POST['user_id']);
            $djSongs = $djSetManager->selectSongByUser($_POST['user_id']);
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
