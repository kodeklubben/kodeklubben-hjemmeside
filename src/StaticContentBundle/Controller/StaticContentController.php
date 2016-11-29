<?php

namespace StaticContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticContentController extends Controller
{
    public function getContentAction($idString)
    {
        $staticContent = $this->getDoctrine()->getRepository('CodeClubBundle:StaticContent')->findOneBy(array('idString' => $idString));
        $content = '';
        if (!is_null($staticContent)) {
            $content = $staticContent->getContent();
        }

        return $this->render('static_content/content.html.twig', array('content' => $content));
    }
}
