<?php

namespace App\Entity;

use App\Repository\EventLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventLikeRepository::class)
 */
class EventLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="ManyToOne")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventLikes")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Evenement
    {
        return $this->event;
    }

    public function setEvent(?Evenement $event): self
    {
        $this->event = $event;

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
}
