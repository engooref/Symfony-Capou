<?php

namespace App\Repository;

use App\Entity\Parcelle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parcelle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parcelle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parcelle[]    findAll()
 * @method Parcelle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcelle::class);
    }
}
