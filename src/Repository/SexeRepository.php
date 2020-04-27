<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Entity\Sexe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sexe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sexe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sexe[]    findAll()
 * @method Sexe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SexeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sexe::class);
    }
}
