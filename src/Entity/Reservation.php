<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tourist;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evenement;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="reservations")
     */
    private $offre;

    /**
     * @ORM\Column(type="date")
     */
    private $date_reservation;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $tarif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTourist(): ?User
    {
        return $this->tourist;
    }

    public function setTourist(?User $tourist): self
    {
        $this->tourist = $tourist;

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

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(?\DateTimeInterface $date_reservation): self
    {
        $this->date_reservation = $date_reservation;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(?float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }
}
