<?php

namespace App\Controller;

use App\Model\SongStyleManager;
use App\Model\StyleManager;
use App\Model\VideoManager;

class VideoController extends AbstractController
{
    public function all(): string
    {
        $videoManager = new VideoManager();
        $styleManager = new StyleManager();
        $videos = $videoManager->selectAll();
        $styles = $styleManager->selectAll();

        return $this->twig->render('Item/video.html.twig', [
            'videos' => $videos,
            'styles' => $styles,
        ]);
    }

    public function style($identifier): string
    {
        $songStyleManager = new SongStyleManager();
        $styleManager = new StyleManager();
        $styles = $styleManager->selectAll();
        $styleName = $styleManager->retrieveStyleName($identifier);
        $videos = $songStyleManager->selectByStyle($identifier);
        return $this->twig->render('Item/videoByStyle.html.twig', [
            'videos' => $videos,
            'styles' => $styles,
            'styleName' => $styleName
        ]);
    }
}
