<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\infoCommande;
use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method infoCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method infoCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method infoCommande[]    findAll()
 * @method infoCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, infoCommande::class);
    }

    public function day(\Datetime $date)
    {
        $from = (Date($date->format("Y-m-d")." 00:00:00"));
        $to   = (Date($date->format("Y-m-d")." 23:59:59"));

        return $this->findVisibleQuery()
            ->andWhere('i.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

    public function week(\Datetime $date)
    {
        $from = (Date("Y-m-d", strtotime("-1 week"))." 00:00:00" );
        $to   = (Date($date->format("Y-m-d")." 23:59:59"));

        return $this->findVisibleQuery()
            ->andWhere('i.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

    public function month(\Datetime $date)
    {
        $from = (Date("Y-m-d", strtotime("-1 month"))." 00:00:00" );
        $to   = (Date($date->format("Y-m-d")." 23:59:59"));

        return $this->findVisibleQuery()
            ->andWhere('i.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('i');
    }
}
