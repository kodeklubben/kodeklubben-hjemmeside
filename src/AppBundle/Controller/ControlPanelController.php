<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ControlPanelController extends Controller
{
    public function showAction()
    {
        return $this->render('control_panel/show.html.twig', array(
            // ...
        ));
    }

}
