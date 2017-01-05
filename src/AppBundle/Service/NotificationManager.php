<?php

namespace AppBundle\Service;

use CourseBundle\Entity\Participant;

class NotificationManager
{
    private $mailer;
    private $twig;

    /**
     * NotificationManager constructor.
     *
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Participant $participant
     */
    public function sendDequeueNotification(Participant $participant)
    {
        $name = $participant->getChild() === null ? 'Du' : $participant->__toString();

        $message = \Swift_Message::newInstance()
            ->setSubject("{$name} har rykket opp fra ventelisten i {$participant->getCourse()}")
            ->setFrom('ikkesvar@kodeklubben.no')
            ->setTo($participant->getUser()->getEmail())
            ->setBody($this->twig->render('@Course/course_queue/dequeue_notification_template.html.twig', array(
                'name' => $name,
                'participant' => $participant,
            )), 'text/html')
            ->setContentType('text/html');

        $this->mailer->send($message);
    }
}
