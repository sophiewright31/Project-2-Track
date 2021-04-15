<?php

namespace App\Controller;

use App\Model\AddSongManager;
class AddSongController extends AbstractController
{
    public function all()
    {
        $addSongManager = new AddSongManager();
        $addSongs = $addSongManager->selectAll();
        return $this->twig->render('AddSong/all.html.twig', [
            'addSongs' => $addSongs,
        ]);
    }

    public function add() : string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['user_id'])) {
                $errors['user_id'] = 'Please enter your id';
            }
            if (empty($_POST['title'])) {
                $errors['title'] = 'Please enter your song title';
            }
            if (empty($_POST['youtube_id'])) {
                $errors['youtube_id'] = 'Please enter your youtube URL';
            }
            $url_string = parse_url($_POST['youtube_id'], PHP_URL_QUERY);
            parse_str($url_string, $args);
            if(isset($args['v'])){
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
        return $this->twig->render('User/addSong.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function success($id)
    {
        $addSongManager = new AddSongManager();
        $addSong = $addSongManager->selectOneById($id);
        //TODO add to Twig
        echo 'Your song ' . $addSong['title'] . ' has been had successfully';
    }
}