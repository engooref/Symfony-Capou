<?php

namespace App\Repository;

use App\Entity\EspTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EspTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method EspTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method EspTest[]    findAll()
 * @method EspTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspTest::class);
    }

    // /**
    //  * @return EspTest[] Returns an array of EspTest objects
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
    public function findOneBySomeField($value): ?EspTest
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
