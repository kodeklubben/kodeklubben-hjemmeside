<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Image;
use AppBundle\Entity\Sponsor;

/**
 * ImageRepository.
 */
class SponsorRepository extends EntityRepository
{
    /**
     * @param Club   $club
     *
     * @return array
     */
    public function findAllByClub(Club $club)
    {
        return $this->createQueryBuilder('sponsor')
            ->select('sponsor')
            ->where('sponsor.club = :club')
            ->setParameter('club', $club)
            ->getQuery()
            ->getArrayResult();
    }
    
    /**
     * @return Sponsor
     */
    public function findById($id)
    {
        return $this->createQueryBuilder('sponsor')
            ->select('sponsor')
            ->where('sponsor.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
