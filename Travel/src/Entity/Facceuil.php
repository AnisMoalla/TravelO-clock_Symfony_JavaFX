<?php

namespace App\Entity;

use App\Repository\FacceuilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FacceuilRepository::class)
 */
class Facceuil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Assert\NotBlank
     */
    private $nb_place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="facceuils")
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=AvisFacc::class, mappedBy="facceuil")
     */
    private $avisFaccs;

    /**
     * @ORM\OneToMany(targetEntity=ReclamationFacc::class, mappedBy="facceuil")
     */
    private $reclamationFaccs;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class, inversedBy="Heberg")
     */
    private $facceuil;

    public function __construct()
    {
        $this->avisFaccs = new ArrayCollection();
        $this->reclamationFaccs = new ArrayCollection();
    }

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(?int $nb_place): self
    {
        $this->nb_place = $nb_place;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|AvisFacc[]
     */
    public function getAvisFaccs(): Collection
    {
        return $this->avisFaccs;
    }

    public function addAvisFacc(AvisFacc $avisFacc): self
    {
        if (!$this->avisFaccs->contains($avisFacc)) {
            $this->avisFaccs[] = $avisFacc;
            $avisFacc->setFacceuil($this);
        }

        return $this;
    }

    public function removeAvisFacc(AvisFacc $avisFacc): self
    {
        if ($this->avisFaccs->removeElement($avisFacc)) {
            // set the owning side to null (unless already changed)
            if ($avisFacc->getFacceuil() === $this) {
                $avisFacc->setFacceuil(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReclamationFacc[]
     */
    public function getReclamationFaccs(): Collection
    {
        return $this->reclamationFaccs;
    }

    public function addReclamationFacc(ReclamationFacc $reclamationFacc): self
    {
        if (!$this->reclamationFaccs->contains($reclamationFacc)) {
            $this->reclamationFaccs[] = $reclamationFacc;
            $reclamationFacc->setFacceuil($this);
        }

        return $this;
    }

    public function removeReclamationFacc(ReclamationFacc $reclamationFacc): self
    {
        if ($this->reclamationFaccs->removeElement($reclamationFacc)) {
            // set the owning side to null (unless already changed)
            if ($reclamationFacc->getFacceuil() === $this) {
                $reclamationFacc->setFacceuil(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getFacceuil(): ?Plan
    {
        return $this->facceuil;
    }

    public function setFacceuil(?Plan $facceuil): self
    {
        $this->facceuil = $facceuil;

        return $this;
    }
}
