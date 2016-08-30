<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\MessageType;
use CodeClubBundle\Entity\Message;
use CodeClubBundle\Entity\Semester;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ControlPanelController.
 * 
 * @Route("/kontrollpanel")
 */
class ControlPanelController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/", name="control_panel")
     */
    public function showAction()
    {
        return $this->render('@Admin/show.html.twig', array(
            // ...
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/epost", name="cp_email")
     */
    public function showEmailAction()
    {
        return $this->render('@Admin/email/show.html.twig');
    }

    public function showMessageAction(Request $request)
    {
        $messages = $this->getDoctrine()->getRepository('CodeClubBundle:Message')->findLatestMessages();

        $message = new Message();
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

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/veiledere", name="cp_tutors")
     */
    public function showTutorsAction(Request $request)
    {
        return $this->renderCourseUsers($request, '@Admin/tutor/show_tutors.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/deltakere", name="cp_participants")
     */
    public function showParticipantsAction(Request $request)
    {
        return $this->renderCourseUsers($request, '@Admin/participant/show_participants.html.twig');
    }

    private function renderCourseUsers(Request $request, $template)
    {
        $semesterId = $request->query->get('semester');
        $semesterRepo = $this->getDoctrine()->getRepository('CodeClubBundle:Semester');
        $semester = is_null($semesterId) ? $semesterRepo->findCurrentSemester() : $semesterRepo->find($semesterId);
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBySemester($semester);
        $semesters = $semesterRepo->findAll();

        return $this->render($template, array(
            'courses' => $courses,
            'semester' => $semester,
            'semesters' => $semesters,
        ));
    }

    public function showUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->findAll();

        return $this->render('@User/control_panel/show_users.html.twig', array(
            'users' => $users,
        ));
    }
}
