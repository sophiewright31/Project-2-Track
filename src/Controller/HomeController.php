<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\AbstractManager;
use App\Model\SongStyleManager;
use App\Model\StyleManager;
use App\Model\SongManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        return $this->twig->render('Home/index.html.twig');
    }

    public function all(): string
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

    public function add(): string
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
                $songManager = new SongManager();
                $songManager->insert($_POST);
                header('Location: /');
            }
        }
        //TODO modifier le chemin pour affichage des erreurs
        return $this->twig->render('User/addSong.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function powerUpById(int $id)
    {
        $songManager = new SongManager();
        $songManager->updatePowerById($id);
        header('Location: /');
        return $this->twig->render('Home/index.html.twig');
    }
}
