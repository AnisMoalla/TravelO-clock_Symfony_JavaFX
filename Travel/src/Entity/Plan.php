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
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="event")
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity=Facceuil::class, mappedBy="facceuil")
     */
    private $Heberg;

    /**
     * @ORM\OneToMany(targetEntity=Hotel::class, mappedBy="hotel")
     */
    private $hotel;

    /**
     * @ORM\OneToMany(targetEntity=Guide::class, mappedBy="guide")
     */
    private $guide;

    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->Heberg = new ArrayCollection();
        $this->hotel = new ArrayCollection();
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
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Evenement $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
            $event->setEvent($this);
        }

        return $this;
    }

    public function removeEvent(Evenement $event): self
    {
        if ($this->event->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getEvent() === $this) {
                $event->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Facceuil[]
     */
    public function getHeberg(): Collection
    {
        return $this->Heberg;
    }

    public function addHeberg(Facceuil $heberg): self
    {
        if (!$this->Heberg->contains($heberg)) {
            $this->Heberg[] = $heberg;
            $heberg->setFacceuil($this);
        }

        return $this;
    }

    public function removeHeberg(Facceuil $heberg): self
    {
        if ($this->Heberg->removeElement($heberg)) {
            // set the owning side to null (unless already changed)
            if ($heberg->getFacceuil() === $this) {
                $heberg->setFacceuil(null);
            }
        }

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
            $hotel->setHotel($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): self
    {
        if ($this->hotel->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getHotel() === $this) {
                $hotel->setHotel(null);
            }
        }

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
            $guide->setGuide($this);
        }

        return $this;
    }

    public function removeGuide(Guide $guide): self
    {
        if ($this->guide->removeElement($guide)) {
            // set the owning side to null (unless already changed)
            if ($guide->getGuide() === $this) {
                $guide->setGuide(null);
            }
        }

        return $this;
    }
}
