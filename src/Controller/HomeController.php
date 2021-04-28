<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\SongStyleManager;
use App\Model\StyleManager;
use App\Model\SongManager;
use App\Model\UserManager;
use App\Service\Badge\GamificationCalculator;

class HomeController extends AbstractController
{
    public function index(): string
    {
        $songManager = new SongManager();
        $styleManager = new StyleManager();
        $topManager = new SongManager();
        $songs = $songManager->selectAll();
        $styles = $styleManager->selectAll();
        $topSongs = $topManager->selectAllTopSong('song.power');

        return $this->twig->render('Home/index.html.twig', [
            'videos' => $songs,
            'styles' => $styles,
            'topSongs' => $topSongs,
        ]);
    }

    public function style($identifier): string
    {
        $songStyleManager = new SongStyleManager();
        $styleManager = new StyleManager();
        $styles = $styleManager->selectAll();
        $styleName = $styleManager->retrieveStyleName($identifier);
        $videos = $songStyleManager->selectByStyle($identifier);
        return $this->twig->render('Home/videoByStyle.html.twig', [
            'videos' => $videos,
            'styles' => $styles,
            'styleName' => $styleName
        ]);
    }

    public function powerUpById(int $id): string
    {
        $songManager = new SongManager();
        $userManager = new UserManager();
        $styleManager = new StyleManager();

        $songManager->updatePowerById($id);
        //TODO  /!\ USER ID HARDCODER /!\
        $userID = 6;
        $userManager->powerUpById($userID);
        $userPower = $userManager->powerByUser($userID);
        $gamificationService = new GamificationCalculator();
        $badgeName = $gamificationService->badgePower($userPower['contribution_force'], $userID);

        $songs = $songManager->selectAll();
        $styles = $styleManager->selectAll();
        $topSongs = $songManager->selectAllTopSong('song.power');

        return $this->twig->render('Home/index.html.twig', [
            'videos' => $songs,
            'styles' => $styles,
            'topSongs' => $topSongs,
            'badgeName' => $badgeName,
        ]);
    }
}
