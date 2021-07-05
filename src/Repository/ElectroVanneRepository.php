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

    /**
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllElectrovanne()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a.id) as value');
        
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}

