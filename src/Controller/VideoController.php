<?php

namespace App\Controller;

use App\Model\VideoManager;

class VideoController extends AbstractController
{
    public function video(): string
    {
        $videoManager = new VideoManager();
        $videos = $videoManager->selectAll();

        return $this->twig->render('Item/video.html.twig', ['videos' => $videos]);
    }


}
