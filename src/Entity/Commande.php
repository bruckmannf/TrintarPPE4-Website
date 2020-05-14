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
     * @var string|null
     *
     * @ORM\Column(name="date_cde", type="string", nullable=true)
     */
    private $dateCde;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facture_pdf", type="string", length=50, nullable=true)
     */
    private $facturePdf;

    /**
     * @var string|null
     *
     * @ORM\Column(name="date_livraison", type="string", nullable=true)
     */
    private $dateLivraison;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prix_total", type="string", length=255, nullable=true)
     */
    private $prixTotal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero_commande", type="string", length=255, nullable=true)
     */
    private $numeroCommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCde(): ?string
    {
        return $this->dateCde;
    }

    public function setDateCde(?string $dateCde): self
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

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(?string $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numeroCommande;
    }

    public function setNumeroCommande(?string $numeroCommande): self
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }

    public function getDateLivraison(): ?string
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?string $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }
}
