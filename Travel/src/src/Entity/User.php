<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Your Password must be at least {{ limit }} characters long",
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $age;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     *
     */
    private $cin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $photo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     *
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */

    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="user")
     *
     */
    private $evenements;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="tourist", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="user")
     * @Assert\NotBlank
     */
    private $avis;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="user")
     * @Assert\NotBlank
     */
    private $reclamations;

    /**
     * @ORM\OneToMany(targetEntity=AvisFacc::class, mappedBy="user")
     */
    private $avisFaccs;

    /**
     * @ORM\OneToMany(targetEntity=ReclamationFacc::class, mappedBy="user")
     */
    private $reclamationFaccs;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="user")
     */
    private $offres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Facceuil", mappedBy="user")
     */
    private $facceuils;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReservationFacceuil", mappedBy="user")
     */
    private $reservationF;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->avisFaccs = new ArrayCollection();
        $this->reclamationFaccs = new ArrayCollection();
        $this->offres = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setUser($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getUser() === $this) {
                $evenement->setUser(null);
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
            $reservation->setTourist($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTourist() === $this) {
                $reservation->setTourist(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setUser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reclamation[]
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

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
            $avisFacc->setUser($this);
        }

        return $this;
    }

    public function removeAvisFacc(AvisFacc $avisFacc): self
    {
        if ($this->avisFaccs->removeElement($avisFacc)) {
            // set the owning side to null (unless already changed)
            if ($avisFacc->getUser() === $this) {
                $avisFacc->setUser(null);
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
            $reclamationFacc->setUser($this);
        }

        return $this;
    }

    public function removeReclamationFacc(ReclamationFacc $reclamationFacc): self
    {
        if ($this->reclamationFaccs->removeElement($reclamationFacc)) {
            // set the owning side to null (unless already changed)
            if ($reclamationFacc->getUser() === $this) {
                $reclamationFacc->setUser(null);
            }
        }

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
            $offre->setUser($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getUser() === $this) {
                $offre->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacceuils() : ?User
    {
        return $this->facceuils;
    }

    /**
     * @param mixed $facceuils
     */
    public function setFacceuils($facceuils): void
    {
        $this->facceuils = $facceuils;
    }

    /**
     * @return mixed
     */
    public function getReservationF() :?ReservationFacceuil
    {
        return $this->reservationF;
    }

    /**
     * @param mixed $reservationF
     */
    public function setReservationF($reservationF): void
    {
        $this->reservationF = $reservationF;
    }




}
