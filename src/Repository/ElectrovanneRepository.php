<?php

namespace App\Repository;

use App\Entity\Electrovanne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Electrovanne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Electrovanne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Electrovanne[]    findAll()
 * @method Electrovanne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElectrovanneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Electrovanne::class);
    }

    // /**
    //  * @return Electrovanne[] Returns an array of Electrovanne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Electrovanne
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
