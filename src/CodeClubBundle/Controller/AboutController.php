<?php

namespace CodeClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller
{
    public function showAction()
    {
        return $this->render('@CodeClub/about/show.html.twig');
    }

}
