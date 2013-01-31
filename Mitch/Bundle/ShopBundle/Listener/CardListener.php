<?php

namespace Mitch\Bundle\ShopBundle\Listener;

use Symfony\Component\EventDispatcher\Event; 
use Symfony\Component\HttpKernel\HttpKernelInterface; 
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class CardListener
{
    public function onCoreController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        // On initalise le panier utilisateur si il n'existe pas encore.
        // Comme on fait ça proprement, la methode est dans le controller, mais
        // ceux-ci dit on aurait put créer une classe panier, mais pour le coup ça ira.
        if (isset($controller[0]) && method_exists($controller[0], 'preExecute')) {
            $controller[0]->preExecute();
        }
    }
}