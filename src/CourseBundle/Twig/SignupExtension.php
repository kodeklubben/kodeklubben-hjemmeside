<?php

namespace CourseBundle\Twig;

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
            'course_availability_color_class' => new \Twig_Function_Method($this, 'courseAvailabilityColorClass'),
            'places_left_color_class' => new \Twig_Function_Method($this, 'placesLeftColorClass'),
        );
    }

    /**
     * @param Tutor[] $tutors
     * @param Course  $course
     *
     * @return bool
     */
    public function isInCourse(array $tutors, Course $course)
    {
        foreach ($tutors as $tutor) {
            if ($tutor->getCourse() === $course) {
                return true;
            }
        }

        return false;
    }

    public function courseAvailabilityColorClass(Course $course)
    {
        $participantCount = count($course->getParticipants());
        $courseAvailability = $course->getParticipantLimit() - $participantCount;
        if ($courseAvailability === 0) {
            return 'text-info';
        } elseif ($participantCount === 0) {
            return 'text-danger';
        } elseif ($courseAvailability > 5 && $participantCount < 5) {
            return 'text-warning';
        } else {
            return 'text-success';
        }
    }

    public function placesLeftColorClass(Course $course)
    {
        $placesLeft = $course->getParticipantLimit() - count($course->getParticipants());
        if ($placesLeft === 0) {
            return 'text-danger';
        } elseif ($placesLeft <= 5) {
            return 'text-warning';
        } else {
            return 'text-success';
        }
    }
}
