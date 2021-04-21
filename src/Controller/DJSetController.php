<?php

namespace App\Controller;

use App\Model\StyleManager;

class DJSetController extends AbstractController
{
    public function index(): string
    {
        $styleManager = new StyleManager();
        $styles = $styleManager->selectAll();
        return $this->twig->render('djset/djhome.html.twig', [
            'styles' => $styles
        ]);
    }
}
