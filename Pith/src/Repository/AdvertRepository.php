<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    /**
     * Returns an array of Advert objects that matches the specified tag.
     *
     * @param string $tag (lost|found)
     * @return Advert[]
     */
    public function findByTag($tag)
    {

        if($tag == 'lost') { 
            $condition = ' IS NOT NULL'; 
        } else {
            $condition = ' IS NULL'; 
        }

        return $this->createQueryBuilder('a')
            ->andwhere('a.isActive = :active')
            ->setParameter(':active', true)
            ->andWhere('a.pet'.$condition)
            ->leftJoin('a.address', 'ad')
            ->addSelect('ad')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
