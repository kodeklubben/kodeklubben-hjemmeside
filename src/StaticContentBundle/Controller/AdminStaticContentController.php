<?php

namespace StaticContentBundle\Controller;

use StaticContentBundle\Entity\StaticContent;
use StaticContentBundle\Form\StaticContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class AdminStaticContentController.
 *
 * @Route("/kontrollpanel")
 */
class AdminStaticContentController extends Controller
{
    public function showInfoAction()
    {
        return $this->render('@StaticContent/control_panel/show_info.html.twig', array(
        ));
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/statisk_innhold/header", name="cp_sc_header")
     */
    public function showHeaderAction(Request $request)
    {
        return $this->_renderForm($request, 'header', 'Header');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/statisk_innhold/tagline", name="cp_sc_tagline")
     */
    public function showTaglineAction(Request $request)
    {
        return $this->_renderForm($request, 'tagline', 'Tagline');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/statisk_innhold/deltaker", name="cp_sc_participant_info")
     */
    public function showParticipantAction(Request $request)
    {
        return $this->_renderForm($request, 'participant_info', 'Deltaker');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/statisk_innhold/veileder", name="cp_sc_tutor_info")
     */
    public function showTutorAction(Request $request)
    {
        return $this->_renderForm($request, 'tutor_info', 'Veileder');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/statisk_innhold/om", name="cp_sc_about")
     */
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

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/statisk_innhold",
     *     options = { "expose" = true },
     *     name="cp_update_static_content"
     * )
     * @Method({"POST"})
     */
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
