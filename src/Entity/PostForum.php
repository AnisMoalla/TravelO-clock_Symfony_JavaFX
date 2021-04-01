<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\PostForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Proxies\__CG__\App\Entity\User as EntityUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostForumRepository::class)
 */
class PostForum
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="postForums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="postForums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="PostF")
     */
    private $forum;

    /**
     * @ORM\OneToMany(targetEntity=Postlike::class, mappedBy="post")
     */
    private $likes;

    public function __construct()
    {
        $this->forum = new ArrayCollection();
        $this->likes = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
     * @return Collection|Commentaire[]
     */
    public function getForum(): Collection
    {
        return $this->forum;
    }

    public function addForum(Commentaire $forum): self
    {
        if (!$this->forum->contains($forum)) {
            $this->forum[] = $forum;
            $forum->setPostF($this);
        }

        return $this;
    }

    public function removeForum(Commentaire $forum): self
    {
        if ($this->forum->removeElement($forum)) {
            // set the owning side to null (unless already changed)
            if ($forum->getPostF() === $this) {
                $forum->setPostF(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Postlike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Postlike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(Postlike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @param \App\Entity\User $user
     * @return boolean
     */
    public function islikebyuser(User $user): bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
