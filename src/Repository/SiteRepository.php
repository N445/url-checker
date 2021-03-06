<?php

namespace App\Repository;

use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Site|null find($id, $lockMode = null, $lockVersion = null)
 * @method Site|null findOneBy(array $criteria, array $orderBy = null)
 * @method Site[]    findAll()
 * @method Site[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }

    /**
     * @param int $id
     * @return Site|null
     * @throws NonUniqueResultException
     */
    public function getSite(int $id)
    {
        return $this->createQueryBuilder('s')
                    ->addSelect('serveur', 'urls')
                    ->andWhere('s.id = :id')
                    ->setParameter('id', $id)
                    ->leftJoin('s.serveur', 'serveur')
                    ->leftJoin('s.urls', 'urls')
                    ->getQuery()
                    ->getOneOrNullResult()
            ;
    }

    /**
     * @return Site[]
     */
    public function getSites()
    {
        return $this->createQueryBuilder('s')
                    ->addSelect('serveur', 'urls')
                    ->leftJoin('s.serveur', 'serveur')
                    ->leftJoin('s.urls', 'urls')
                    ->getQuery()
                    ->getResult()
            ;
    }

    // /**
    //  * @return Site[] Returns an array of Site objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Site
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
