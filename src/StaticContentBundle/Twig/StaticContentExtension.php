<?php

namespace StaticContentBundle\Twig;

use StaticContentBundle\Entity\StaticContent;

class StaticContentExtension extends \Twig_Extension
{
    protected $doctrine;
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }
    public function getName()
    {
        return 'StaticContentExtension';
    }
    public function getFunctions()
    {
        return array(
            'get_content' => new \Twig_Function_Method($this, 'getContent'),
        );
    }
    public function getContent($stringId)
    {
        $staticContent = $this->doctrine
            ->getEntityManager()
            ->getRepository('StaticContentBundle:StaticContent')
            ->findOneByStringId($stringId);
        if (!$staticContent) {
            //Makes new record for requested htmlID
            $newStaticContent = new StaticContent();
            $newStaticContent->setIdString($stringId);
            $newStaticContent->setContent('Dette er en ny statisk tekst for: '.$stringId);
            $em = $this->doctrine->getEntityManager();
            $em->persist($newStaticContent);
            $em->flush();

            return 'Dette er en ny statisk tekst for: '.$stringId;
        }

        return $staticContent->getContent();
    }
}
