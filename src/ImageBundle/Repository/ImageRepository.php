<?php

namespace ImageBundle\Repository;

use CodeClubBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use ImageBundle\Entity\Image;

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
        return $this->createQueryBuilder('image')
            ->select('image')
            ->where('image.club = :club')
            ->andWhere('image.name = :name')
            ->setParameter('club', $club)
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
