<?php

namespace App\Repository;

use App\Entity\Magasin;
use App\Entity\MagasinSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Magasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Magasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Magasin[]    findAll()
 * @method Magasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Magasin::class);
    }

    /**
     * @return Magasin[]
     */

    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('m');
    }

    /**
     * @return Query
     */

    public function findAllVisibleQuery(MagasinSearch $search): Query
    {
        $query =  $this->findVisibleQuery();

        if ($search->getNom()){
            $query = $query
                ->andWhere('m.nom = :nom')
                ->setParameter('nom', $search->getNom());
        }

        if ($search->getOptions()->count()>0){
            foreach($search->getOptions() as $k => $option){
                $query=$query
                    ->andWhere(":option$k MEMBER OF m.idOptionMagasin")
                    ->setParameter("option$k",$option);
            }
        }

        if($search->getLat() && $search->getLng() && $search->getDistance()){
            $query = $query
                ->select('m')
                ->andWhere('(6353 * 2 * ASIN(SQRT( POWER(SIN((m.lat - :lat) *  pi()/180 / 2), 2) +COS(m.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((m.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
                ->setParameter('lng', $search->getLng())
                ->setParameter('lat', $search->getLat())
                ->setParameter('distance', $search->getDistance());
        }

        return $query->getQuery();
    }

    // /**
    //  * @return Magasin[] Returns an array of Magasin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Magasin
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
