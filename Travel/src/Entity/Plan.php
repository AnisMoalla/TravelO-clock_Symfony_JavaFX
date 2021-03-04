<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plan
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Description;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, inversedBy="Events")
     */
    private $Event;

    /**
     * @ORM\ManyToMany(targetEntity=Hotel::class, inversedBy="Hotels")
     */
    private $hotel;

    /**
     * @ORM\ManyToMany(targetEntity=Facceuil::class)
     */
    private $facceuil;

    /**
     * @ORM\ManyToMany(targetEntity=Guide::class, inversedBy="guides")
     */
    private $guide;

    public function __construct()
    {
        $this->Event = new ArrayCollection();
        $this->hotel = new ArrayCollection();
        $this->facceuil = new ArrayCollection();
        $this->guide = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvent(): Collection
    {
        return $this->Event;
    }

    public function addEvent(Evenement $event): self
    {
        if (!$this->Event->contains($event)) {
            $this->Event[] = $event;
        }

        return $this;
    }

    public function removeEvent(Evenement $event): self
    {
        $this->Event->removeElement($event);

        return $this;
    }

    /**
     * @return Collection|Hotel[]
     */
    public function getHotel(): Collection
    {
        return $this->hotel;
    }

    public function addHotel(Hotel $hotel): self
    {
        if (!$this->hotel->contains($hotel)) {
            $this->hotel[] = $hotel;
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): self
    {
        $this->hotel->removeElement($hotel);

        return $this;
    }

    /**
     * @return Collection|Facceuil[]
     */
    public function getFacceuil(): Collection
    {
        return $this->facceuil;
    }

    public function addFacceuil(Facceuil $facceuil): self
    {
        if (!$this->facceuil->contains($facceuil)) {
            $this->facceuil[] = $facceuil;
        }

        return $this;
    }

    public function removeFacceuil(Facceuil $facceuil): self
    {
        $this->facceuil->removeElement($facceuil);

        return $this;
    }

    /**
     * @return Collection|Guide[]
     */
    public function getGuide(): Collection
    {
        return $this->guide;
    }

    public function addGuide(Guide $guide): self
    {
        if (!$this->guide->contains($guide)) {
            $this->guide[] = $guide;
        }

        return $this;
    }

    public function removeGuide(Guide $guide): self
    {
        $this->guide->removeElement($guide);

        return $this;
    }
}
