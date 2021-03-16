<?php

namespace App\Repository;

use App\Entity\DonneesPiquet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DonneesPiquet|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonneesPiquet|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonneesPiquet[]    findAll()
 * @method DonneesPiquet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonneesPiquetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonneesPiquet::class);
    }

    // /**
    //  * @return DonneesPiquet[] Returns an array of DonneesPiquet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DonneesPiquet
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
