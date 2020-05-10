<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\ProduitSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function day(\Datetime $date)
    {
        $from = (Date($date->format("Y-m-d")." 00:00:00"));
        $to   = (Date($date->format("Y-m-d")." 23:59:59"));

        return $this->findVisibleQuery()
            ->andWhere('p.createdAt BETWEEN :from AND :to')
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
            ->andWhere('p.createdAt BETWEEN :from AND :to')
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
            ->andWhere('p.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Produit[]
     */

    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->orderBy("p.id", "DESC")
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    /**
     * @return Query
     */

    public function findAllVisibleQuery(ProduitSearch $search): Query
    {
        $query =  $this->findVisibleQuery();

        if ($search->getCategories()->count()>0){
            foreach($search->getCategories() as $k => $category){
                $query=$query
                    ->andWhere(":category$k MEMBER OF p.idCategorie")
                    ->setParameter("category$k",$category);
            }
        }

        if ($search->getLibelle()){
            $query = $query
                ->andWhere('p.libelle = :libelle')
                ->setParameter('libelle', $search->getLibelle());
        }

        if ($search->getAuteurs()->count()>0){
            foreach($search->getAuteurs() as $k => $auteur){
                $query=$query
                    ->andWhere(":auteur$k MEMBER OF p.idAuteur")
                    ->setParameter("auteur$k",$auteur);
            }
        }

        if ($search->getLicences()->count()>0){
            foreach($search->getLicences() as $k => $licence){
                $query=$query
                    ->andWhere(":licence$k MEMBER OF p.idLicence")
                    ->setParameter("licence$k",$licence);
            }
        }

        return $query->getQuery();
    }
}
