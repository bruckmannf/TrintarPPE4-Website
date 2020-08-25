<?php

namespace App\Entity;

use App\Entity\Magasin;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Categorie
 *
 * @ORM\Table(name="masque")
 * @UniqueEntity("libelle")
 * @ORM\Entity(repositoryClass="App\Repository\MasqueRepository")
 */
class Masque
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Magasin", inversedBy="masques", orphanRemoval=true)
     */
    private $idMagasin;

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
     * @return Collection|Masque[]
     */
    public function getIdMagasin(): Collection
    {
        return $this->idMagasin;
    }

    public function setIdMagasin(?Collection $idMagasin): self
    {
        $this->idMagasin = $idMagasin;

        return $this;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function removeIdMagasin(Magasin $magasin): self
    {
        if ($this->idMagasin->contains($magasin)) {
            $this->idMagasin->removeElement($magasin);
            $magasin->removeMasque($this);
        }

        return $this;
    }
}
