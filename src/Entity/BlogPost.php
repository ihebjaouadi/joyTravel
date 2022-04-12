<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 */
class BlogPost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="remplir ce champs")
     * @ORM\Column(type="string", length=30)
     */
    private $titre;

    /**
     * @Assert\NotBlank(message="remplir ce champs")
     * @ORM\Column(type="string", length=30)
     */
    private $description;

    /**
     * @Assert\NotBlank(message="remplir ce champs")
     * @ORM\Column(type="string", length=1000)
     */
    private $body;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=BlogCommentaires::class, mappedBy="post", orphanRemoval=true)
     */
    private $blogCommentaires;

    public function __construct()
    {
        $this->blogCommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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
     * @return Collection<int, BlogCommentaires>
     */
    public function getBlogCommentaires(): Collection
    {
        return $this->blogCommentaires;
    }

    public function addBlogCommentaire(BlogCommentaires $blogCommentaire): self
    {
        if (!$this->blogCommentaires->contains($blogCommentaire)) {
            $this->blogCommentaires[] = $blogCommentaire;
            $blogCommentaire->setPost($this);
        }

        return $this;
    }

    public function removeBlogCommentaire(BlogCommentaires $blogCommentaire): self
    {
        if ($this->blogCommentaires->removeElement($blogCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($blogCommentaire->getPost() === $this) {
                $blogCommentaire->setPost(null);
            }
        }

        return $this;
    }
}
