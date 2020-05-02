<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur", indexes={@ORM\Index(name="id_image", columns={"id_image"}), @ORM\Index(name="id_role", columns={"id_role"})})
 * @ORM\Entity
 * @UniqueEntity("email")
 * @Vich\Uploadable()
 */
class Utilisateur implements UserInterface, \Serializable

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
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @Assert\Length(min=4, max=255)
     * @Assert\Email()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;


    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=50, nullable=true)
     */
    private $telephone;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string|null
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var File|null
     * @Assert\Image (
     *      mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="utilisateur_image", fileNameProperty="filename")
     */

    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="float", scale=4, precision=7)
     */
    private $lng;

    /**
     * @ORM\Column(type="float", scale=4, precision=6)
     */
    private $lat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_postal", type="string", length=250, nullable=true)
     */
    private $codePostal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="confirmation_token", type="string", length=50, nullable=true)
     */
    private $confirmationToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="enabled", type="string", length=50)
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sexe", inversedBy="utilisateurs")
     */
    private $idSexe;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande", inversedBy="utilisateurs")
     */
    private $idCommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
     * @return Utilisateur
     */
    public function setFilename(?string $filename): Utilisateur
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
     * @return Utilisateur
     */
    public function setImageFile(?File $imageFile): Utilisateur
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return serialize([$this->id, $this->email, $this->password]);
    }
    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->email, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng ($lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat($lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }
    /**
     * @return Collection|Sexe[]
     */
    public function getIdSexe(): ?Collection
    {
        return $this->idSexe;
    }

    public function setIdSexe(?Collection $idSexe): self
    {
        $this->idSexe = $idSexe;

        return $this;
    }

    public function removeIdSexe(Sexe $sexe): self
    {
        if ($this->idSexe->contains($sexe)) {
            $this->idSexe->removeElement($sexe);
            $sexe->removeIdUtilisateur($this);
        }

        return $this;
    }

    public function getEnabled(): ?string
    {
        return $this->enabled;
    }

    public function setEnabled(?string $enabled): self
    {
        $this->enabled = $enabled;

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
            $commande->removeIdUtilisateur($this);
        }

        return $this;
    }

}
