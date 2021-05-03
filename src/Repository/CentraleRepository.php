<?php

namespace App\Repository;

use App\Entity\Centrale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Centrale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Centrale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Centrale[]    findAll()
 * @method Centrale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentraleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Centrale::class);
    }

    // /**
    //  * @return Centrale[] Returns an array of Centrale objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Centrale
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
