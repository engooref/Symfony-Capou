<?php

namespace App\Repository;

use App\Entity\DonneesArmoire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DonneesArmoire|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonneesArmoire|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonneesArmoire[]    findAll()
 * @method DonneesArmoire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonneesArmoireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonneesArmoire::class);
    }

    // /**
    //  * @return DonneesArmoire[] Returns an array of DonneesArmoire objects
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
    public function findOneBySomeField($value): ?DonneesArmoire
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
