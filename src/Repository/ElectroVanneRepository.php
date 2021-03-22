<?php

namespace App\Repository;

use App\Entity\ElectroVanne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ElectroVanne|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElectroVanne|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElectroVanne[]    findAll()
 * @method ElectroVanne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElectroVanneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElectroVanne::class);
    }

    // /**
    //  * @return ElectroVanne[] Returns an array of ElectroVanne objects
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
    public function findOneBySomeField($value): ?ElectroVanne
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

