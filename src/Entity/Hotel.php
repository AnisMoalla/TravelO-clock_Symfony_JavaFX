<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=HotelRepository::class)
 */
class Hotel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="nom", type="string", length=200, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;


    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="adresse", type="string", length=200, nullable=false)
     */
    private $adresse;


    /**
     * @var integer
     * @Assert\Range(
     *      min = 100,
     *      minMessage = "Nombre de Chambre doit etre inferieur a {{ limit }}",
     * )
     *
     * @ORM\Column(name="nbrechambre", type="integer", length=200, nullable=false)
     */
    private $nbrechambre;


    /**
     * @var integer
     * @ORM\Column(name="nbrechambreDispo", type="integer", length=200, nullable=false)
     */
    private $nbrechambreDispo;

    /**
     * @var integer
     *
     * @Assert\Range(
     *      min = 2,
     *      max=5,
     *      minMessage = "Nombre de etoile doit etre entre a 2 et 5",
     * )
     * @ORM\Column(name="nbreEtoile", type="integer", length=200, nullable=false)
     */
    private $nbreEtoile;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", length=65535, nullable=false)
     */
    private $image;

    /**
     * @Assert\File(maxSize="500000000k")
     */
    public  $file;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="hotels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="hotel")
     */
    private $promotions;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
    }


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
     * @return string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return int
     */
    public function getNbrechambre(): ?int
    {
        return $this->nbrechambre;
    }

    /**
     * @param int $nbrechambre
     */
    public function setNbrechambre(int $nbrechambre): void
    {
        $this->nbrechambre = $nbrechambre;
    }

    /**
     * @return int
     */
    public function getNbrechambreDispo(): ?int
    {
        return $this->nbrechambreDispo;
    }

    /**
     * @param int $nbrechambreDispo
     */
    public function setNbrechambreDispo(int $nbrechambreDispo): void
    {
        $this->nbrechambreDispo = $nbrechambreDispo;
    }

    /**
     * @return int
     */
    public function getNbreEtoile(): ?int
    {
        return $this->nbreEtoile;
    }

    /**
     * @param int $nbreEtoile
     */
    public function setNbreEtoile(int $nbreEtoile): void
    {
        $this->nbreEtoile = $nbreEtoile;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
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
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setHotel($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            // set the owning side to null (unless already changed)
            if ($promotion->getHotel() === $this) {
                $promotion->setHotel(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }




}
