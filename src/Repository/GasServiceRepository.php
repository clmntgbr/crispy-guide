<?php

namespace App\Repository;

use App\Entity\GasService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GasService|null find($id, $lockMode = null, $lockVersion = null)
 * @method GasService|null findOneBy(array $criteria, array $orderBy = null)
 * @method GasService[]    findAll()
 * @method GasService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GasServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GasService::class);
    }
}
