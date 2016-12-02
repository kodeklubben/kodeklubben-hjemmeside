<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Semester;
use Doctrine\ORM\EntityManager;

class SemesterExtension extends \Twig_Extension
{
    private $manager;

    /**
     * SemesterExtension constructor.
     *
     * @param EntityManager $manager
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
            new \Twig_SimpleFunction('get_all_semesters', array($this, 'getAllSemesters')),
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
