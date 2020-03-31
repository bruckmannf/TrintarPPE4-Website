<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class MagasinSearch {

    /**
     * @var string|null
     */

    private $nom;

    /**
     * @var ArrayCollection
     */

    private $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    /**
     * @return null|string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param null|string $libelle
     * @return MagasinSearch
     */
    public function setNom(?string $nom): MagasinSearch
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return ArrayCollection $options
     */

    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    /**
     * @param ArrayCollection $options
     */

    public function setOptions (ArrayCollection $options): void
    {
        $this->options = $options;
    }
}
