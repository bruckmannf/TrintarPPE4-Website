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
     * @var ArrayCollection
     */

    private $auteurs;

    /**
     * @var ArrayCollection
     */

    private $licences;

    public function __construct()

    {
        $this->categories = new ArrayCollection();
        $this->auteurs = new ArrayCollection();
        $this->licences = new ArrayCollection();
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
     * @return ArrayCollection $auteurs
     */

    public function getAuteurs(): ArrayCollection
    {
        return $this->auteurs;
    }

    /**
     * @param ArrayCollection $auteurs
     */

    public function setAuteurs (ArrayCollection $auteurs): void
    {
        $this->auteurs = $auteurs;
    }

    /**
     * @return ArrayCollection $licences
     */

    public function getLicences(): ArrayCollection
    {
        return $this->licences;
    }

    /**
     * @param ArrayCollection $licences
     */

    public function setLicences (ArrayCollection $licences): void
    {
        $this->licences = $licences;
    }
}
