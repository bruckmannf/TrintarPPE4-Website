<?php

namespace App\Entity;

use App\Form\ProduitType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use App\Entity\Categorie;
use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;




/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @UniqueEntity("libelle")
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 * @Vich\Uploadable()
 */
class Produit
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
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true, unique=true)
     */
    private $libelle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="synopsis", type="text", length=1000, nullable=true)
     */
    private $synopsis;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prixht", type="string", length=50, nullable=true)
     */
    private $prixht;

    /**
     * @var string|null
     *
     * @ORM\Column(name="stock", type="string", length=50, nullable=true)
     */
    private $stock;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", inversedBy="produits")
     */
    private $idCategorie;

    /**
     * @ORM\ManyToMany(targetEntity="Licence", inversedBy="produits")
     */
    private $idLicence;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Auteur", inversedBy="produits")
     */
    private $idAuteur;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Magasin", inversedBy="produits")
     */
    private $idMagasin;

    /**
     * @var string|null
     * @ORM\Column(name="filename", type="string", length=255)
     */

    private $filename;

    /**
     * @var File|null
     * @Assert\Image (
     *      mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="filename")
     */

    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function __construct()
    {
        $this->idImage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function getPrixht(): ?string
    {
        return $this->prixht;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->libelle);
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function setPrixht(?string $prixht): self
    {
        $this->prixht = $prixht;

        return $this;
    }

    public function setStock(?string $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getIdCategorie(): ?Collection
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Collection $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    public function removeIdCategorie(Categorie $categorie): self
    {
        if ($this->idCategorie->contains($categorie)) {
            $this->idCategorie->removeElement($categorie);
            $categorie->removeIdProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Licence[]
     */
    public function getIdLicence(): ?Collection
    {
        return $this->idLicence;
    }

    public function setIdLicence(?Collection $idLicence): self
    {
        $this->idLicence = $idLicence;

        return $this;
    }

    public function removeIdLicence(Licence $licence): self
    {
        if ($this->idLicence->contains($licence)) {
            $this->idLicence->removeElement($licence);
            $licence->removeProduit($this);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     * @return Produit
     */
    public function setFilename(?string $filename): Produit
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return Produit
     */
    public function setImageFile(?File $imageFile): Produit
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    /**
     * @return Collection|Auteur[]
     */
    public function getIdAuteur(): ?Collection
    {
        return $this->idAuteur;
    }

    public function setIdAuteur(?Collection $idAuteur): self
    {
        $this->idAuteur = $idAuteur;

        return $this;
    }

    public function removeIdAuteur(Auteur $auteur): self
    {
        if ($this->idAuteur->contains($auteur)) {
            $this->idAuteur->removeElement($auteur);
            $auteur->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Magasin[]
     */
    public function getIdMagasin(): ?Collection
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
            $magasin->removeProduit($this);
        }

        return $this;
    }
}
