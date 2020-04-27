<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Sexe
 *
 * @ORM\Table(name="sexe")
 * @UniqueEntity("libelle")
 * @ORM\Entity(repositoryClass="App\Repository\SexeRepository")
 */
class Sexe
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", inversedBy="sexes")
     */
    private $idUtilisateur;

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
     * @return Collection|Utilisateur[]
     */
    public function getIdUtilisateur(): Collection
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Collection $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function removeIdUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->idUtilisateur->contains($utilisateur)) {
            $this->idUtilisateur->removeElement($utilisateur);
            $utilisateur->removeIdSexe($this);
        }
        return $this;
    }
}
