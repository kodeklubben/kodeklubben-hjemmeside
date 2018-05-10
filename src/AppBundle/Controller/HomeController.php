<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="home")
     * @Method("GET")
     */
    public function showAction()
    {
        return $this->render('home/show.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="home_w_domain",
     *     host="{subdomain}.{domain}",
     *     defaults={"subdomain"="", "domain"="%base_host%"},
     *     requirements={"subdomain"="\w+", "domain"="%base_host%"}
     *     )
     * @Method("GET")
     */
    public function showWithDomainAction()
    {
        return $this->showAction();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showMessagesAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findLatestMessages($club);

        return $this->render('home/messages.html.twig', array('messages' => $messages));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCourseTypesAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $courseTypes = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findNotHiddenBySemester($currentSemester, $club);

        return $this->render('home/course.html.twig', array('courseTypes' => $courseTypes));
    }

    /**
     * @param null $week
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTimeTableAction($week = null)
    {
        if (is_null($week)) {
            $week = (new \DateTime())->format('W');
        }
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $courseClasses = $this->getDoctrine()->getRepository('AppBundle:CourseClass')->findByWeek($week, $currentSemester, $club);
        $allCourseClasses = $this->getDoctrine()->getRepository('AppBundle:CourseClass')->findBySemester($currentSemester);

        $firstClass = reset($allCourseClasses);
        $lastClass = end($allCourseClasses);
        $coursesHasStarted = $firstClass !== false && $firstClass->getTime() < new \DateTime();
        $coursesHasEnded = $lastClass !== false && $lastClass->getTime() < new \DateTime();

        return $this->render('home/time_table.html.twig', array(
            'courseClasses' => $courseClasses,
            'week' => $week,
            'currentSemester' => $currentSemester,
            'firstClass' => $firstClass,
            'lastClass' => $lastClass,
            'coursesHasStarted' => $coursesHasStarted,
            'coursesHasEnded' => $coursesHasEnded
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showSponsorsAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $clubSponsors = $this->getDoctrine()->getRepository('AppBundle:Sponsor')->findAllByClub($club);

        return $this->render('home/sponsors.html.twig', array(
            'sponsors' => $clubSponsors,
        ));
    }
}
