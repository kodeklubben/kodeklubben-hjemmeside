<?php

namespace CourseBundle\Twig;

use CodeClubBundle\Service\ClubManager;
use CourseBundle\Entity\Course;
use UserBundle\Entity\Tutor;

class SignupExtension extends \Twig_Extension
{

    public function __construct()
    {
    }

    public function getName()
    {
        return 'SignupExtension';
    }

    public function getFunctions()
    {
        return array(
            'is_in_course' => new \Twig_Function_Method($this, 'isInCourse'),
        );
    }

    /**
     * @param Tutor[] $tutors
     * @param Course $course
     * @return bool
     */
    public function isInCourse(array $tutors, Course $course)
    {
        foreach($tutors as $tutor) {
            if ($tutor->getCourse() === $course) {
                return true;
            }
        }
        return false;
    }
}
