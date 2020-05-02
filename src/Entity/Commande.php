<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;


/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}), @ORM\Index(name="id_adresse", columns={"id_adresse"})})
 * @ORM\Entity
 */
class Commande
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_cde", type="date", nullable=true)
     */
    private $dateCde;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facture_pdf", type="string", length=50, nullable=true)
     */
    private $facturePdf;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_livraison", type="date", nullable=true)
     */
    private $dateLivraison;

    /**
     * @var string|null
     *
     * @ORM\Column(name="quantite_totale", type="string", length=255, nullable=true)
     */
    private $quantiteTotale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prix_total", type="string", length=255, nullable=true)
     */
    private $prixTotal;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit", inversedBy="commandes")
     */
    private $idProduit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Magasin", inversedBy="commandes")
     */
    private $idMagasin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", inversedBy="commandes")
     */
    private $idUtilisateur;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\infoCommande", inversedBy="commandes")
     */
    private $idInfoCommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCde(): ?\DateTimeInterface
    {
        return $this->dateCde;
    }

    public function setDateCde(?\DateTimeInterface $dateCde): self
    {
        $this->dateCde = $dateCde;

        return $this;
    }

    public function getFacturePdf(): ?string
    {
        return $this->facturePdf;
    }

    public function setFacturePdf(?string $facturePdf): self
    {
        $this->facturePdf = $facturePdf;

        return $this;
    }

    public function getQuantiteTotale(): ?string
    {
        return $this->quantiteTotale;
    }

    public function setQuantiteTotale(?string $quantiteTotale): self
    {
        $this->quantiteTotale = $quantiteTotale;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(?string $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

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
            $produit->removeIdCommande($this);
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

    public function removeIdMagasin(Magasin $magasin): self
    {
        if ($this->idMagasin->contains($magasin)) {
            $this->idMagasin->removeElement($magasin);
            $magasin->removeIdCommande($this);
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getIdUtilisateur(): ?Collection
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Collection $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function removeIdUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->idUtilisateur->contains($utilisateur)) {
            $this->idUtilisateur->removeElement($utilisateur);
            $utilisateur->removeIdCommande($this);
        }

        return $this;
    }

    /**
     * @return Collection|infoCommande[]
     */
    public function getIdInfoCommande(): ?Collection
    {
        return $this->idInfoCommande;
    }

    public function setIdInfoCommande(?Collection $idInfoCommande): self
    {
        $this->idInfoCommande = $idInfoCommande;

        return $this;
    }

    public function removeIdInfoCommande(infoCommande $infoCommande): self
    {
        if ($this->idInfoCommande->contains($infoCommande)) {
            $this->idInfoCommande->removeElement($infoCommande);
            $infoCommande->removeIdCommande($this);
        }

        return $this;
    }

}
