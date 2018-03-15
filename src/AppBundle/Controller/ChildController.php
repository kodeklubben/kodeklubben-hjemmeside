<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Child;
use AppBundle\Entity\User;
use AppBundle\Form\Type\ChildType;

class ChildController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/barn/ny", name="child_create")
     * @Method({"GET", "POST"})
     */
    public function createChildAction(Request $request)
    {
        $child = new Child();
        $form = $this->createForm(ChildType::class, $child);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $child->setParent($this->getUser());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($child);
            $manager->flush();

            return $this->redirectToRoute('sign_up');
        }

        return $this->render('course/sign_up/create_child.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kontrollpanel/barn/ny/{user}", name="cp_child_create")
     * @Method({"GET", "POST"})
     */
    public function adminCreateChildAction(User $user, Request $request)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($user);

        $child = new Child();
        $form = $this->createForm(ChildType::class, $child);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $child->setParent($user);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($child);
            $manager->flush();

            return $this->redirectToRoute('cp_sign_up', array('id' => $user->getId()));
        }

        return $this->render('course/control_panel/sign_up/create_child.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * @param Child   $child
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/barn/slett/{id}",
     *     requirements={"id" = "\d+"},
     *     name="child_delete"
     * )
     * @Method("POST")
     */
    public function deleteChildAction(Child $child, Request $request)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($child);

        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        //A parent can only delete their own children
        if ($child->getParent()->getId() == $this->getUser()->getId() || $isAdmin) {
            $childParticipants = $this->getDoctrine()->getRepository('AppBundle:Participant')->findBy(array('child' => $child));
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
