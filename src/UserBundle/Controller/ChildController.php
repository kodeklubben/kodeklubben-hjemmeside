<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Child;
use UserBundle\Entity\User;
use UserBundle\Form\ChildType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ChildController extends Controller
{
    public function createChildAction(Request $request)
    {
        $child = new Child();
        $form = $this->createForm(new ChildType(), $child);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $child->setParent($this->getUser());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($child);
            $manager->flush();

            return $this->redirectToRoute('sign_up');
        }

        return $this->render('@CodeClub/sign_up/create_child.html.twig', array('form' => $form->createView()));
    }

    public function adminCreateChildAction(User $user, Request $request)
    {
        $child = new Child();
        $form = $this->createForm(new ChildType(), $child);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $child->setParent($user);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($child);
            $manager->flush();

            return $this->redirectToRoute('cp_sign_up', array('id' => $user->getId()));
        }

        return $this->render('@Course/control_panel/sign_up/create_child.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    public function deleteChildAction(Child $child, Request $request)
    {
        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        //A parent can only delete their own children
        if ($child->getParent()->getId() == $this->getUser()->getId() || $isAdmin) {
            $childParticipants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('child' => $child));
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($child);
            //Remove all child participation
            foreach ($childParticipants as $participant) {
                $manager->remove($participant);
            }
            $manager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
