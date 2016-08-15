<?php

namespace StaticContentBundle\Controller;

use StaticContentBundle\Entity\StaticContent;
use StaticContentBundle\Form\StaticContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminStaticContentController extends Controller
{
    public function showInfoAction()
    {
        return $this->render('@StaticContent/control_panel/show_info.html.twig', array(
        ));
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

    private function _renderForm(Request $request, $idString, $label)
    {
        $content = $this->getDoctrine()->getRepository('StaticContentBundle:StaticContent')->findOneBy(array('idString' => $idString));
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

            return $this->redirectToRoute('cp_sc_'.$idString);
        }

        return $this->render('@StaticContent/control_panel/show_'.$idString.'.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function updateAction(Request $request)
    {
        $idString = $request->request->get('idString');
        $content = $request->request->get('content');
        if (is_null($idString) || is_null($content)) {
            return $this->_createErrorResponse('Invalid POST parameters');
        }

        $staticContent = $this->getDoctrine()->getRepository('StaticContentBundle:StaticContent')->findOneBy(array('idString' => $idString));
        if (is_null($staticContent)) {
            return $this->_createErrorResponse('Static Content not found');
        }

        $staticContent->setContent($content);
        $staticContent->setLastEdited(new \DateTime());
        $staticContent->setLastEditedBy($this->getUser());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($staticContent);
        $manager->flush();

        return $this->_createSuccessResponse();
    }

    private function _createErrorResponse($error)
    {
        $response = new Response(json_encode(array('success' => false, 'error' => $error)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function _createSuccessResponse()
    {
        $response = new Response(json_encode(array('success' => true, 'error' => false)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
