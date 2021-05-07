<?php

namespace App\Service\Form;

use App\Model\SongManager;

class VideoCheck extends FormCheck
{
    public function videoExistThisMonth(string $videoId): bool
    {
        $songManager = new SongManager();
        $monthSongs = $songManager->selectAll();
        foreach ($monthSongs as $monthSong) {
            if ($monthSong['youtube_id'] === $videoId) {
                $this->errors['videoExist'] = 'La musique que vous proposez a déjà été proposé ce mois-ci';
                return true;
            }
        }
        return false;
    }

    public function extractYoutubeId(string $url): string
    {
        $urlString = parse_url($url, PHP_URL_QUERY);
        $args = [];
        parse_str($urlString, $args);
        if (isset($args['v'])) {
            $result = $args['v'];
        } else {
            $result = $this->errors['IncorrectUrl'] = 'Please enter a valid youtube URL';
        }
        return $result;
    }

    public function modifyPost(array $post, string $videoId): array
    {
        $id = $_SESSION['id'];
        $post['user_id'] = $id;
        $post['youtube_id'] = $videoId;
        return $post;
    }
}
