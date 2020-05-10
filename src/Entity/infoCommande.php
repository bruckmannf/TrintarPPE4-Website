<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * infoCommande
 *
 * @ORM\Table(name="info_commande")
 * @ORM\Entity
 */
class infoCommande
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
     * @var string|null
     *
     * @ORM\Column(name="quantite", type="string", length=50, nullable=true)
     */
    private $quantite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prix_unitaire", type="string", length=50, nullable=true)
     */
    private $prixUnitaire;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit", inversedBy="infocommandes")
     */
    private $idProduit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande", inversedBy="infocommandes")
     */
    private $idCommande;

    /**
     * @var string|null
     *
     * @ORM\Column(name="created_at", type="string", nullable=true)
     */
    private $createdAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(?string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(?string $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getIdProduit(): ?Collection
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Collection $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function removeIdProduit(Produit $produit): self
    {
        if ($this->idProduit->contains($produit)) {
            $this->idProduit->removeElement($produit);
            $produit->removeIdInfoCommande($this);
        }

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getIdCommande(): ?Collection
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Collection $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function removeIdCommande(Commande $commande): self
    {
        if ($this->idCommande->contains($commande)) {
            $this->idCommande->removeElement($commande);
            $commande->removeIdInfoCommande($this);
        }

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
