<?php

namespace AppBundle\Service;

use AppBundle\Entity\Course;
use AppBundle\Entity\CourseType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CourseManager
{
    /**
     * @param Course|CourseType $obj
     */
    public function throw404ifCourseOrCourseTypeIsDeleted($obj)
    {
        if (
            $obj !== null &&
            $obj->isDeleted() ||
            ($obj instanceof Course && $obj->getCourseType()->isDeleted())
        ) {
            throw new NotFoundHttpException();
        }
    }
}
