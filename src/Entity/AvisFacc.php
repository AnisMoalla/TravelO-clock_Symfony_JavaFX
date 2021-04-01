<?php

namespace App\Entity;

use App\Repository\AvisFaccRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=AvisFaccRepository::class)
 */
class AvisFacc
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $date_publication;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="avisFaccs")
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Facceuil::class, inversedBy="avisFaccs")
     * @Assert\NotBlank
     */
    private $facceuil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

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

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(?\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

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
