<?php

namespace App\Repository;

use App\Entity\Piquet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Piquet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Piquet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Piquet[]    findAll()
 * @method Piquet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PiquetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Piquet::class);
    }

    // /**
    //  * @return Piquet[] Returns an array of Piquet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Piquet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
