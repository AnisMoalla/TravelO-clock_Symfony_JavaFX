<?php

namespace App\Entity;

use App\Repository\ReclamationFaccRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ReclamationFaccRepository::class)
 */
class ReclamationFacc
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $date_reclamation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reclamationFaccs")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Facceuil::class, inversedBy="reclamationFaccs")
     */
    private $facceuil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(?\DateTimeInterface $date_reclamation): self
    {
        $this->date_reclamation = $date_reclamation;

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

    public function getFacceuil(): ?Facceuil
    {
        return $this->facceuil;
    }

    public function setFacceuil(?Facceuil $facceuil): self
    {
        $this->facceuil = $facceuil;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
