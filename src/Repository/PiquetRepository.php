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

    /**
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllPiquet()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a.id) as value');
        
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
