<?php

namespace AppBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findBoxProducts($box)
    {
        return $this->createQueryBuilder('p')
                ->join('p.boxProduct', 'bp')
                ->select('p, bp')
                ->where('bp.box = :box')->setParameter('box', $box)
                ->getQuery()
                ->getResult();
    }
}
