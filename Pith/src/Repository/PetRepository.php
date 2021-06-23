<?php

namespace App\Repository;

use App\Entity\Pet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pet[]    findAll()
 * @method Pet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pet::class);
    }

    /**
     * Returns an array of Pet objects that matches the specified query.
     *
     * @param string $query the search input value
     * @return Pet[]
     */
    public function findByCriteria($criteria, $query)
    {
        return $this->createQueryBuilder('p')
            ->andwhere('p.'.$criteria.'= :query')
            ->setParameter(':query', $query)
            ->getQuery()
            ->getResult()
        ;
    }
}
