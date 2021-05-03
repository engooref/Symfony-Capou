<?php

namespace App\Repository;

use App\Entity\DonneesVanne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DonneesVanne|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonneesVanne|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonneesVanne[]    findAll()
 * @method DonneesVanne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonneesVanneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonneesVanne::class);
    }

    // /**
    //  * @return DonneesVanne[] Returns an array of DonneesVanne objects
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
    public function findOneBySomeField($value): ?DonneesVanne
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
