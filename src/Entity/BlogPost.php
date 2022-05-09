<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 */
class BlogPost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("g")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="remplir ce champs")
     * @ORM\Column(type="string", length=30)
     * @Groups("g")
     */
    private $titre;

    /**
     * @Assert\NotBlank(message="remplir ce champs")
     * @ORM\Column(type="string", length=30)
     * @Groups("g")
     */
    private $description;

    /**
     * @Assert\NotBlank(message="remplir ce champs")
     * @ORM\Column(type="string", length=1000)
     * @Groups("g")
     */
    private $body;

    /**
     * @ORM\Column(type="date")
     * @Groups("g")
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

    /**
     * @ORM\OneToMany(targetEntity=RapportBlogPost::class, mappedBy="post", orphanRemoval=true)
     */
    private $rapportBlogPosts;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="post", orphanRemoval=true)
     */
    private $PostLikes;

    public function __construct()
    {
        $this->blogCommentaires = new ArrayCollection();
        $this->rapportBlogPosts = new ArrayCollection();
        $this->PostLikes = new ArrayCollection();
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

    /**
     * @return Collection<int, RapportBlogPost>
     */
    public function getRapportBlogPosts(): Collection
    {
        return $this->rapportBlogPosts;
    }

    public function addRapportBlogPost(RapportBlogPost $rapportBlogPost): self
    {
        if (!$this->rapportBlogPosts->contains($rapportBlogPost)) {
            $this->rapportBlogPosts[] = $rapportBlogPost;
            $rapportBlogPost->setPost($this);
        }

        return $this;
    }

    public function removeRapportBlogPost(RapportBlogPost $rapportBlogPost): self
    {
        if ($this->rapportBlogPosts->removeElement($rapportBlogPost)) {
            // set the owning side to null (unless already changed)
            if ($rapportBlogPost->getPost() === $this) {
                $rapportBlogPost->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostLike>
     */
    public function getPostLikes(): Collection
    {
        return $this->PostLikes;
    }

    public function addPostLike(PostLike $PostLike): self
    {
        if (!$this->PostLikes->contains($PostLike)) {
            $this->PostLikes[] = $PostLike;
            $PostLike->setPost($this);
        }

        return $this;
    }

    public function removePostLike(PostLike $PostLike): self
    {
        if ($this->PostLikes->removeElement($PostLike)) {
            // set the owning side to null (unless already changed)
            if ($PostLike->getPost() === $this) {
                $PostLike->setPost(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        foreach ($this->PostLikes as $like) {
            if ($like->getUser() === $user) return true;
        }
        return false;
    }
}
