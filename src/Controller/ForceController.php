<?php

namespace App\Controller;

use App\Model\ForceManager;

class ForceController extends AbstractController
{
    public function raiseUp()
    {
        $raiseUp = new ForceManager();
        $power = $raiseUp->songPower();
        return $this->twig->render('Bouton/boutonForce.html.twig', ['power' => $power]);
    }

    public function raiseUpId(int $id)
    {
        $forceManager = new ForceManager();
        $forceManager->updatePowerById($id);
        header('Location: /');
        return $this->twig->render('Home/index.html.twig');
    }
}
