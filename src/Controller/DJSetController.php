<?php

namespace App\Controller;

class DJSetController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('djset/djhome.html.twig');
    }
}
