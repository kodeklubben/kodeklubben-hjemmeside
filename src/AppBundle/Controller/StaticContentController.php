<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Entity\StaticContent;
use AppBundle\Form\StaticContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticContentController extends Controller
{
    public function getContentAction($idString)
    {
        $staticContent = $this->getDoctrine()->getRepository('AppBundle:StaticContent')->findOneBy(array('idString' => $idString));
        $content = '';
        if (!is_null($staticContent)) {
            $content = $staticContent->getContent();
        }
        return $this->render('static_content/content.html.twig', array('content' => $content));
    }

    public function showHeaderAction(Request $request)
    {
        return $this->_renderForm($request, 'header', 'Header');
    }

    public function showTaglineAction(Request $request)
    {
        return $this->_renderForm($request, 'tagline', 'Tagline');
    }

    public function showParticipantAction(Request $request)
    {
        return $this->_renderForm($request, 'participant_info', 'Deltaker');
    }

    public function showTutorAction(Request $request)
    {
        return $this->_renderForm($request, 'tutor_info', 'Veileder');
    }

    public function showAboutAction(Request $request)
    {
        return $this->_renderForm($request, 'about', 'Om oss');
    }

    public function updateAction(Request $request)
    {
        $idString = $request->request->get('idString');
        $content = $request->request->get('content');
        if(is_null($idString) || is_null($content))return $this->_createErrorResponse('Invalid POST parameters');

        $staticContent = $this->getDoctrine()->getRepository('AppBundle:StaticContent')->findOneBy(array('idString' => $idString));
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

    private function _renderForm(Request $request, $idString, $label)
    {
        $capitalizedCamelIdString = $this->_snakeToCamel($idString);
        $content = $this->getDoctrine()->getRepository('AppBundle:StaticContent')->findOneBy(array('idString' => $idString));
        if (is_null($content)) {
            $content = new StaticContent();
            $content->setIdString($idString);
        }

        $form = $this->createForm(new StaticContentType($label), $content);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $content->setLastEditedBy($this->getUser());
            $content->setLastEdited(new \DateTime());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($content);
            $manager->flush();
            return $this->redirectToRoute('cp_sc_' . $idString);
        }
        return $this->render('control_panel/static_content/show' . $capitalizedCamelIdString . '.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function _snakeToCamel($str)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
    }
}
