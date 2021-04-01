<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Reclamation::class)
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $reclamation;

    /**
     * @ORM\OneToOne(targetEntity=ReclamationFacc::class)
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $reclamationFamille;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }

    public function getReclamationFamille(): ?ReclamationFacc
    {
        return $this->reclamationFamille;
    }

    public function setReclamationFamille(?ReclamationFacc $reclamationFamille): self
    {
        $this->reclamationFamille = $reclamationFamille;

        return $this;
    }
}
