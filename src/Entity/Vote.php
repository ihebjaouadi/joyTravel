<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoteRepository::class)
 */
class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $vote;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="vote", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_user;

    /**
     * @ORM\OneToOne(targetEntity=Hotel::class, inversedBy="vote", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_hotel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getIDUser(): ?User
    {
        return $this->ID_user;
    }

    public function setIDUser(User $ID_user): self
    {
        $this->ID_user = $ID_user;

        return $this;
    }

    public function getIDHotel(): ?Hotel
    {
        return $this->ID_hotel;
    }

    public function setIDHotel(Hotel $ID_hotel): self
    {
        $this->ID_hotel = $ID_hotel;

        return $this;
    }
}
