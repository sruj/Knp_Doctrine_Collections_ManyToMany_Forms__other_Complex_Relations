<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Genus;
use Doctrine\ORM\EntityRepository;

class GenusRepository extends EntityRepository
{
    /**
     * @return Genus[]
     */
    public function findAllPublishedOrderedByRecentlyActive()
    {
        return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->leftJoin('genus.notes', 'genus_note')
            ->orderBy('genus_note.createdAt', 'DESC')
            //JOIN bez LAZY, - wszystkie dane dostępne w kontrolerze na dzień dobry
//            ->leftJoin('genus.genusScientists', 'genus_scientists')
//            ->addSelect('genus_scientists')
            ->getQuery()
            ->execute();
    }
}
