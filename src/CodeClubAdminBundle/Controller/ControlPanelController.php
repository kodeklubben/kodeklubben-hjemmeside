<?php

namespace CodeClubAdminBundle\Controller;

use CodeClubAdminBundle\Form\MessageType;
use CodeClubBundle\Entity\Message;
use CodeClubBundle\Entity\Semester;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ControlPanelController extends Controller
{
    public function showAction()
    {
        return $this->render('@CodeClubAdmin/show.html.twig', array(
            // ...
        ));
    }

    public function showEmailAction(){
        return $this->render('@CodeClubAdmin/email/show.html.twig');
    }

    public function showMessageAction(Request $request){
        $messages = $this->getDoctrine()->getRepository('CodeClubBundle:Message')->findLatestMessages();

        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('cp_message');
        }
        return $this->render('@CodeClubAdmin/message/show_message.html.twig',array(
            'messages' => $messages,
            'form' => $form->createView(),
        ));
    }

    public function deleteMessageAction($id){
        $manager = $this->getDoctrine()->getManager();
        $message = $manager->getRepository('CodeClubBundle:Message')->find($id);
        if(!is_null($message)){
            $manager->remove($message);
            $manager->flush();
        }
        return $this->redirectToRoute('cp_message');
    }
    
    public function showTutorsAction(Request $request)
    {
        return $this->renderCourseUsers($request, "@CodeClubAdmin/tutor/show_tutors.html.twig");
    }


    public function showParticipantsAction(Request $request)
    {
        return $this->renderCourseUsers($request, "@CodeClubAdmin/participant/show_participants.html.twig");

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
            'users' => $users
        ));
    }

}
