<?php

namespace StaticContentBundle\Controller;

use StaticContentBundle\Entity\StaticContent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function updateAction(Request $request)
    {
        $idString = $request->request->get('idString');
        $content = $request->request->get('content');
        if(is_null($idString) || is_null($content))return $this->_createErrorResponse('Invalid POST parameters');

        $staticContent = $this->getDoctrine()->getRepository('StaticContentBundle:StaticContent')->findOneBy(array('idString' => $idString));
        if(is_null($staticContent))return $this->_createErrorResponse('Static Content not found');

        $staticContent->setContent($content);
        $staticContent->setLastEdited(new \DateTime());
        $staticContent->setLastEditedBy($this->getUser());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($staticContent);
        $manager->flush();

        return $this->_createSuccessResponse();
    }

    private function _createErrorResponse($error){
        $response = new Response(json_encode(array('success' => false, 'error' => $error)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function _createSuccessResponse(){
        $response = new Response(json_encode(array('success' => true, 'error' => false)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
