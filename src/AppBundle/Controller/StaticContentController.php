<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;

class StaticContentController extends Controller
{
    public function getContentAction($idString){
        $content = $this->getDoctrine()->getRepository('AppBundle:StaticContent')->findOneBy(array('idString' => $idString))->getContent();
        return $this->render('static_content/content.html.twig', array('content' => $content));
    }
}
