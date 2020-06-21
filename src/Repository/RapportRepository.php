<?php

namespace App\Repository;

use App\Entity\Rapport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rapport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rapport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rapport[]    findAll()
 * @method Rapport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rapport::class);
    }

    /**
     * @param Rapport   $rapport
     * @param \DateTime $limit
     * @return Rapport[]
     */
    public function getLastSameRapport(Rapport $rapport, \DateTime $limit)
    {
        return $this->createQueryBuilder('r')
                    ->addSelect('url')
                    ->andWhere('url.url = :url')
                    ->setParameter('url', $rapport->getUrl()->getUrl())
                    ->andWhere('r.createdAt > :date')
                    ->setParameter('date', $limit)
                    ->andWhere('r.errorCode = :errorCode')
                    ->setParameter('errorCode', $rapport->getErrorCode())
                    ->leftJoin('r.url', 'url')
                    ->getQuery()
                    ->getResult()
            ;
    }

    /**
     * @return Rapport[]
     */
    public function getUnsendRapport()
    {
        return $this->createQueryBuilder('r')
                    ->addSelect('site', 'url')
//                    ->andWhere('r.isSend = false')
                    ->leftJoin('r.url', 'url')
                    ->leftJoin('url.site', 'site')
                    ->getQuery()
                    ->getResult()
            ;
    }

    // /**
    //  * @return Rapport[] Returns an array of Rapport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rapport
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
