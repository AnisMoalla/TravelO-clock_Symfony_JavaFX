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
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $nb_place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="facceuils")
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
     * @ORM\OneToMany(targetEntity="App\Entity\ReservationFacceuil", mappedBy="facceuil")
     */
    private $reservationF;


    /**
     *
     * @ORM\Column(name="rate", type="float", nullable=true)
     */
    private $rate;

    /**
     *
     * @ORM\Column(name="vote", type="integer", nullable=true)
     */
    private $vote;


    /**
     * @Assert\File(maxSize="500000000k")
     */
    public  $file;


    public function getWebpath(){


        return null === $this->image ? null : $this->getUploadDir.'/'.$this->image;
    }
    protected  function  getUploadRootDir(){

        return __DIR__.'/../../../Travel/public/Upload'.$this->getUploadDir();
    }
    protected function getUploadDir(){

        return'';
    }
    public function getUploadFile(){
        if (null === $this->getFile()) {
            $this->image = "3.jpg";
            return;
        }


        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()

        );

        // set the path property to the filename where you've saved the file
        $this->image = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }



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

    /**
     * @return mixed
     */
    public function getEtat() : ?string
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getRate() :?float
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getVote() :?int
    {
        return $this->vote;
    }

    /**
     * @param mixed $vote
     */
    public function setVote($vote): void
    {
        $this->vote = $vote;
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
