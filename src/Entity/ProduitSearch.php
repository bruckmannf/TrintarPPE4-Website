<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ProduitSearch {

    /**
     * @var ArrayCollection
     */

    private $categories;

    /**
     * @var string|null
     */

    private $libelle;

    /**
     * @var string|null
     */

    private $auteur;

    public function __construct()

    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return ArrayCollection $categories
     */

    public function getCategories(): ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     */

    public function setCategories (ArrayCollection $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return null|string
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param null|string $libelle
     * @return ProduitSearch
     */
    public function setLibelle(?string $libelle): ProduitSearch
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    /**
     * @param null|string $auteur
     * @return ProduitSearch
     */
    public function setAuteur(?string $auteur): ProduitSearch
    {
        $this->auteur = $auteur;
        return $this;
    }
}
