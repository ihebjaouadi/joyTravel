<?php

namespace App\Entity;

use App\Repository\PostlikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostlikeRepository::class)
 */
class Postlike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="Likes")
     */
    private $Post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Commentaire
    {
        return $this->Post;
    }

    public function setPost(?Commentaire $Post): self
    {
        $this->Post = $Post;

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

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

}
