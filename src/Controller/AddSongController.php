<?php

namespace App\Controller;

use App\Model\AddSongManager;

class AddSongController extends AbstractController
{
    public function all(): string
    {
        $addSongManager = new AddSongManager();
        $addSongs = $addSongManager->selectAll();
        return $this->twig->render('AddSong/all.html.twig', [
            'addSongs' => $addSongs,
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
                echo $args['v'];
            } else {
                $errors['youtube_id'] = 'Please enter a valid youtube URL';
            }

            if (empty($errors)) {
                $addSongManager = new AddSongManager();
                $addSongManager->insert($_POST);
                header('Location: /');
            }
        }
        //TODO modifier le chemin pour affichage des erreurs
        return $this->twig->render('User/addSong.html.twig', [
            'errors' => $errors,
        ]);
    }
}
