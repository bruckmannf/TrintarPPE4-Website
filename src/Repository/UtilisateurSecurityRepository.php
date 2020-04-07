<?php

namespace App\Repository;

use App\Entity\UtilisateurSecurity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UtilisateurSecurity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UtilisateurSecurity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UtilisateurSecurity[]    findAll()
 * @method UtilisateurSecurity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurSecurityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UtilisateurSecurity::class);
    }
}




