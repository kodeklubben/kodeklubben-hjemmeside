<?php

namespace StaticContentBundle\Twig;

use CodeClubBundle\Service\ClubManager;
use Doctrine\ORM\EntityManager;
use StaticContentBundle\Entity\StaticContent;

class StaticContentExtension extends \Twig_Extension
{
    protected $manager;
    private $clubManager;

    public function __construct(EntityManager $manager, ClubManager $clubManager)
    {
        $this->manager = $manager;
        $this->clubManager = $clubManager;
    }
    public function getName()
    {
        return 'StaticContentExtension';
    }
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_content', array($this, 'getContent')),
        );
    }
    public function getContent($stringId)
    {
        $club = $this->clubManager->getCurrentClub();
        $staticContent = $this->manager
            ->getRepository('StaticContentBundle:StaticContent')
            ->findOneByStringId($stringId, $club);
        if (!$staticContent) {
            //Makes new record for requested htmlID
            $newStaticContent = new StaticContent();
            $newStaticContent->setIdString($stringId);
            $newStaticContent->setContent('Dette er en ny statisk tekst for: '.$stringId);
            $newStaticContent->setClub($club);
            $this->manager->persist($newStaticContent);
            $this->manager->flush();

            return 'Dette er en ny statisk tekst for: '.$stringId;
        }

        return $staticContent->getContent();
    }
}
