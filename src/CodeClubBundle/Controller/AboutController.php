<?php

namespace CodeClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller
{
    public function showAction()
    {
        $response = $this->render('@CodeClub/about/show.html.twig');

        // Set cache expiration time to 5 minutes
//        $response->setSharedMaxAge(300);

//        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

}
