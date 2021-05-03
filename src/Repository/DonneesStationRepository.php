<?php

namespace App\Repository;

use App\Entity\DonneesStation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DonneesStation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonneesStation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonneesStation[]    findAll()
 * @method DonneesStation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonneesStationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonneesStation::class);
    }

    // /**
    //  * @return DonneesStation[] Returns an array of DonneesStation objects
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
    public function findOneBySomeField($value): ?DonneesStation
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
