<?php

namespace App\Controller;

class ErrorHandleController extends AbstractController
{
    public function pageNotFound(): string
    {
        return $this->twig->render('error/error404.html.twig');
    }

    public function badMethod(): string
    {
        return $this->twig->render('error/error405.html.twig');
    }

    public function notAuthorized(): string
    {
        return $this->twig->render('error/error401.html.twig');
    }

    public function forbidden(): string
    {
        return $this->twig->render('error/error403.html.twig');
    }
}
