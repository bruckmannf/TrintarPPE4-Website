<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Entity\Masque;
use App\Entity\optionMagasin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Masque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Masque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Masque[]    findAll()
 * @method Masque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Masque::class);
    }
}
