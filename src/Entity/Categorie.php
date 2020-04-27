<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @UniqueEntity("libelle")
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\Column(name="libelle", type="string", length=50, nullable=true, unique=true)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit", inversedBy="categories")
     */
    private $idProduit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getIdProduit(): Collection
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Collection $idProduit): self
    {
        $this->idProduitd = $idProduit;

        return $this;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function removeIdProduit(Produit $produit): self
    {
        if ($this->idProduit->contains($produit)) {
            $this->idProduit->removeElement($produit);
            $produit->removeCategorie($this);
        }

        return $this;
    }
}
