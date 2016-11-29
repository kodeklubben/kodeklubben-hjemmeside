<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Semester;
use Doctrine\ORM\EntityManager;

class SemesterExtension extends \Twig_Extension
{
    private $manager;

    /**
     * SemesterExtension constructor.
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function getName()
    {
        return 'SemesterExtension';
    }

    public function getFunctions()
    {
        return array(
            'get_all_semesters' => new \Twig_Function_Method($this, 'getAllSemesters'),
        );
    }

    /**
     * @return Semester[]
     */
    public function getAllSemesters()
    {
        return $this->manager->getRepository('AppBundle:Semester')->findAll();
    }
}
