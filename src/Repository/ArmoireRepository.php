<?php

namespace App\Repository;

use App\Entity\Armoire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Armoire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Armoire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Armoire[]    findAll()
 * @method Armoire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArmoireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Armoire::class);
    }

    /**
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllArmoire()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a.id) as value');
        
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
