<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $Name;

    
    private $post;

    /**
     * @ORM\OneToMany(targetEntity=PostForum::class, mappedBy="category", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $postForums;

    public function __construct()
    {
        $this->post = new ArrayCollection();
        $this->postForums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection|PostForum[]
     */
    public function getPostForums(): Collection
    {
        return $this->postForums;
    }

    public function addPostForum(PostForum $postForum): self
    {
        if (!$this->postForums->contains($postForum)) {
            $this->postForums[] = $postForum;
            $postForum->setCategory($this);
        }

        return $this;
    }

    public function removePostForum(PostForum $postForum): self
    {
        if ($this->postForums->removeElement($postForum)) {
            // set the owning side to null (unless already changed)
            if ($postForum->getCategory() === $this) {
                $postForum->setCategory(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->Name;
    }
  
}
