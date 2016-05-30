<?php

namespace CodeClubAdminBundle\Controller;

use CodeClubAdminBundle\Form\InfoType;
use StaticContentBundle\Entity\StaticContent;
use StaticContentBundle\Form\StaticContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaticContentController extends Controller
{
    public function showInfoAction(Request $request){
        $region = $this->getDoctrine()->getRepository('StaticContentBundle:StaticContent')->findOneByStringId('region');
        $regionForm = $this->createForm(new InfoType(), $region);
        $regionForm->handleRequest($request);
        if($regionForm->isSubmitted() && $regionForm->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($region);
            $manager->flush();
            return $this->render('@CodeClubAdmin/static_content/show_info.html.twig', array(
                'regionForm' => $regionForm->createView(),
            ));
        }
        return $this->render('@CodeClubAdmin/static_content/show_info.html.twig', array(
            'regionForm' => $regionForm->createView(),
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
            return $this->redirectToRoute('cp_sc_' . $idString);
        }
        return $this->render('@CodeClubAdmin/static_content/show_' . $idString . '.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
