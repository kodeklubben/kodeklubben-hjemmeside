<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CourseType;
use AppBundle\Entity\Message;
use AppBundle\Form\CourseTypeType;
use AppBundle\Form\InfoType;
use AppBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ControlPanelController extends Controller
{
    public function showAction()
    {
        return $this->render('control_panel/show.html.twig', array(
            // ...
        ));
    }

    public function showEmailAction(){
        return $this->render('control_panel/email/show.html.twig');
    }

    public function showMessageAction(Request $request){
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findLatestMessages();

        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('cp_message');
        }
        return $this->render('control_panel/showMessage.html.twig',array(
            'messages' => $messages,
            'form' => $form->createView(),
        ));
    }

    public function deleteMessageAction($id){
        $manager = $this->getDoctrine()->getManager();
        $message = $manager->getRepository('AppBundle:Message')->find($id);
        if(!is_null($message)){
            $manager->remove($message);
            $manager->flush();
        }
        return $this->redirectToRoute('cp_message');
    }

    public function showInfoAction(Request $request){
        $region = $this->getDoctrine()->getRepository('AppBundle:StaticContent')->findOneBy(array('idString' => 'region'));
        $regionForm = $this->createForm(new InfoType(), $region);
        $regionForm->handleRequest($request);
        if($regionForm->isSubmitted() && $regionForm->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($region);
            $manager->flush();
            return $this->render('control_panel/static_content/showInfo.html.twig', array(
                'regionForm' => $regionForm->createView(),
            ));
        }
        return $this->render('control_panel/static_content/showInfo.html.twig', array(
            'regionForm' => $regionForm->createView(),
        ));
    }

}
