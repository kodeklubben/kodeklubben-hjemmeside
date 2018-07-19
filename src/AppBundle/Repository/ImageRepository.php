<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Image;

/**
 * ImageRepository.
 */
class ImageRepository extends EntityRepository
{
    /**
     * @param Club   $club
     * @param string $name
     *
     * @return Image
     */
    public function findByClubAndName(Club $club, $name)
    {
        $image =  $this->createQueryBuilder('image')
            ->select('image')
            ->where('image.club = :club')
            ->andWhere('image.name = :name')
            ->setParameter('club', $club)
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
            
        if ($image === null) {
            $image = Image::getPlaceholderImage($club, $name);
        }
        
        return $image;
    }
}
