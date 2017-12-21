<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllScientists()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isScientist = :isScientist')
            ->setParameter('isScientist', 1);
    }

}
