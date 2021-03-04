<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
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
    private $date_reclamtion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reclamations")
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="reclamations")
     * @Assert\NotBlank
     */
    private $evenement;

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

    public function getDateReclamtion(): ?\DateTimeInterface
    {
        return $this->date_reclamtion;
    }

    public function setDateReclamtion(?\DateTimeInterface $date_reclamtion): self
    {
        $this->date_reclamtion = $date_reclamtion;

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

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }
}
