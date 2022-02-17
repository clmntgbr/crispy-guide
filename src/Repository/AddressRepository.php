<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function findCities()
    {
        return $this->createQueryBuilder('a')
            ->select('LOWER(MAX(a.city)) as name, a.postalCode')
            ->orderBy('LOWER(MAX(a.city))', 'ASC')
            ->groupBy('a.postalCode')
            ->getQuery()
            ->getResult()
        ;
    }
}
