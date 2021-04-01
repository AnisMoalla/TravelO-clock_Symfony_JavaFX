<?php

namespace App\Entity;

use App\Repository\CalanderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalanderRepository::class)
 */
class Calander
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
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $date_begin;

    /**
     * @ORM\Column(type="date")
     */
    private $data_fin;

    /**
     * @ORM\ManyToOne(targetEntity=Guide::class, inversedBy="res")
     */
    private $Guide;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDateBegin(): ?\DateTimeInterface
    {
        return $this->date_begin;
    }

    public function setDateBegin(\DateTimeInterface $date_begin): self
    {
        $this->date_begin = $date_begin;

        return $this;
    }

    public function getDataFin(): ?\DateTimeInterface
    {
        return $this->data_fin;
    }

    public function setDataFin(\DateTimeInterface $data_fin): self
    {
        $this->data_fin = $data_fin;

        return $this;
    }

    public function getGuide(): ?Guide
    {
        return $this->Guide;
    }

    public function setGuide(?Guide $Guide): self
    {
        $this->Guide = $Guide;

        return $this;
    }
}
