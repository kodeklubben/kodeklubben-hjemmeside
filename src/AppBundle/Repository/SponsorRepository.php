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
     * @param string $name
     *
     * @return Sponsor
     */
    public function findAllByClub(Club $club)
    {
        return $this->createQueryBuilder('sponsor')
            ->select('sponsor')
            ->where('sponsor.club = :club')
            ->setParameter('club', $club)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
