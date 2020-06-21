<?php

namespace App\Repository;

use App\Entity\Serveur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Serveur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serveur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serveur[]    findAll()
 * @method Serveur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serveur::class);
    }

    /**
     * @return Serveur[]
     */
    public function getServers()
    {
        return $this->createQueryBuilder('s')
                    ->addSelect('sites')
                    ->leftJoin('s.sites', 'sites')
                    ->getQuery()
                    ->getResult()
            ;
    }

    /**
     * @param int $id
     * @return Serveur|null
     * @throws NonUniqueResultException
     */
    public function getServer(int $id)
    {
        return $this->createQueryBuilder('s')
                    ->addSelect('sites')
                    ->leftJoin('s.sites', 'sites')
                    ->where('s.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Serveur[] Returns an array of Serveur objects
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
    public function findOneBySomeField($value): ?Serveur
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
