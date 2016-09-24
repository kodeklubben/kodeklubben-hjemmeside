<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\MessageType;
use AppBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class MessageController.
 *
 * @Route("/kontrollpanel")
 */
class MessageController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/melding", name="cp_message")
     */
    public function showAction(Request $request)
    {
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findLatestMessages();

        $message = new Message();
        $message->setClub($this->get('app.club_finder')->getCurrentClub());

        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('cp_message');
        }

        return $this->render('@App/control_panel/show_message.html.twig', array(
            'messages' => $messages,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/melding/slett/{id}",
     *     name="cp_delete_message",
     *     requirements={"id" = "\d+"}
     * )
     */
    public function deleteMessageAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $message = $manager->getRepository('AppBundle:Message')->find($id);
        if (!is_null($message)) {
            $manager->remove($message);
            $manager->flush();
        }

        return $this->redirectToRoute('cp_message');
    }
}
