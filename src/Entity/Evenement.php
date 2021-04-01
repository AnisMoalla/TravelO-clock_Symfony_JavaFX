<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
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
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $date_fin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $nbr_places;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="evenement", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $offres;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="evenement", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $reservations;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vote;

    /**
     * @ORM\OneToMany(targetEntity=EvenementCommentaire::class, mappedBy="event")
     */
    private $evenementCommentaires;

    /**
     * @ORM\OneToMany(targetEntity=EventLike::class, mappedBy="event")
     */
    private $ManyToOne;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->evenementCommentaires = new ArrayCollection();
        $this->ManyToOne = new ArrayCollection();
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbrPlaces(): ?int
    {
        return $this->nbr_places;
    }

    public function setNbrPlaces(?int $nbr_places): self
    {
        $this->nbr_places = $nbr_places;

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
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setEvenement($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getEvenement() === $this) {
                $offre->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setEvenement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEvenement() === $this) {
                $reservation->setEvenement(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(?int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * @return Collection|EvenementCommentaire[]
     */
    public function getEvenementCommentaires(): Collection
    {
        return $this->evenementCommentaires;
    }

    public function addEvenementCommentaire(EvenementCommentaire $evenementCommentaire): self
    {
        if (!$this->evenementCommentaires->contains($evenementCommentaire)) {
            $this->evenementCommentaires[] = $evenementCommentaire;
            $evenementCommentaire->setEvent($this);
        }

        return $this;
    }

    public function removeEvenementCommentaire(EvenementCommentaire $evenementCommentaire): self
    {
        if ($this->evenementCommentaires->removeElement($evenementCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($evenementCommentaire->getEvent() === $this) {
                $evenementCommentaire->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventLike[]
     */
    public function getManyToOne(): Collection
    {
        return $this->ManyToOne;
    }

    public function addManyToOne(EventLike $manyToOne): self
    {
        if (!$this->ManyToOne->contains($manyToOne)) {
            $this->ManyToOne[] = $manyToOne;
            $manyToOne->setEvent($this);
        }

        return $this;
    }

    public function removeManyToOne(EventLike $manyToOne): self
    {
        if ($this->ManyToOne->removeElement($manyToOne)) {
            // set the owning side to null (unless already changed)
            if ($manyToOne->getEvent() === $this) {
                $manyToOne->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function islikebyuser(User $user): bool
    {
        foreach ($this->ManyToOne as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
