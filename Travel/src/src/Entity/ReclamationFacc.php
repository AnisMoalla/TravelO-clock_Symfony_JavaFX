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
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $date_reclamation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reclamationFaccs")
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Facceuil::class, inversedBy="reclamationFaccs")
     * @Assert\NotBlank
     */
    private $facceuil;

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
}
