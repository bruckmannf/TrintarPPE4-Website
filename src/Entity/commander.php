<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * commander
 *
 * @ORM\Table(name="commander")
 * @ORM\Entity
 */

class commander
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="produit_id", type="string", length=50, nullable=true, unique=true)
     */
    private $produitId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="commande_id", type="string", length=50, nullable=true, unique=true)
     */
    private $commandeId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="utilisateur_id", type="string", length=50, nullable=true, unique=true)
     */
    private $utilisateurId;

    public function getProduitId(): ?int
    {
        return $this->produitId;
    }

    public function setProduitId(?int $produitId): self
    {
        $this->produitId = $produitId;

        return $this;
    }

    public function getCommandeId(): ?int
    {
        return $this->commandeId;
    }

    public function setCommandeId(?int $commandeId): self
    {
        $this->commandeId = $commandeId;

        return $this;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?int $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;

        return $this;
    }

}