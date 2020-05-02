<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\infoCommande;
use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
}
