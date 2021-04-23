<?php

namespace App\Controller;

class PageNotFoundController extends AbstractController
{
    public function pageNotFound(): string
    {
        return $this->twig->render('error/error404.html.twig');
    }
}
