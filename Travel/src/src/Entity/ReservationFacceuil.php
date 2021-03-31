<?php

namespace App\Entity;

use App\Repository\ReservationFacceuilRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationFacceuilRepository::class)
 */
class ReservationFacceuil
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="facceuils")
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Facceuil", inversedBy="facceuils")
     */
    private $facceuil;

    /**
     * @return mixed
     */
    public function getUser():? User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getFacceuil():? Facceuil
    {
        return $this->facceuil;
    }

    /**
     * @param mixed $facceuil
     */
    public function setFacceuil($facceuil): void
    {
        $this->facceuil = $facceuil;
    }





}
