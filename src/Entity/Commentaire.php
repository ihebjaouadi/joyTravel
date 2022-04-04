<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_user;

    /**
     * @ORM\ManyToOne(targetEntity=Hotel::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_hotel;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $Content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getIDUser(): ?User
    {
        return $this->ID_user;
    }

    public function setIDUser(?User $ID_user): self
    {
        $this->ID_user = $ID_user;

        return $this;
    }

    public function getIDHotel(): ?Hotel
    {
        return $this->ID_hotel;
    }

    public function setIDHotel(?Hotel $ID_hotel): self
    {
        $this->ID_hotel = $ID_hotel;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }
}
