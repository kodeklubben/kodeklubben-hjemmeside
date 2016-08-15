<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\MessageType;
use CodeClubBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller
{
    public function showAction(Request $request)
    {
        $messages = $this->getDoctrine()->getRepository('CodeClubBundle:Message')->findLatestMessages();

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

        return $this->render('@Admin/message/show_message.html.twig', array(
            'messages' => $messages,
            'form' => $form->createView(),
        ));
    }

    public function deleteMessageAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $message = $manager->getRepository('CodeClubBundle:Message')->find($id);
        if (!is_null($message)) {
            $manager->remove($message);
            $manager->flush();
        }

        return $this->redirectToRoute('cp_message');
    }
}
