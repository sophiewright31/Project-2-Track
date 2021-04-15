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
use App\Model\VideoManager;

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
        $videoManager = new VideoManager();
        $styleManager = new StyleManager();
        $videos = $videoManager->selectAll();
        $styles = $styleManager->selectAll();

        return $this->twig->render('Home/index.html.twig', [
            'videos' => $videos,
            'styles' => $styles,
        ]);
    }

    public function style($identifier): string
    {
        $songStyleManager = new SongStyleManager();
        $styleManager = new StyleManager();
        $styles = $styleManager->selectAll();
        $styleName = $styleManager->styleName($identifier);
        $videos = $songStyleManager->selectByStyle($identifier);
        return $this->twig->render('Item/videoByStyle.html.twig', [
            'videos' => $videos,
            'styles' => $styles,
            'styleName' => $styleName
        ]);
    }
}
