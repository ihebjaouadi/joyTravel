<?php

namespace App\Entity;

use App\Repository\ReservationEvenementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationEvenementRepository::class)
 */
class ReservationEvenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservationEvenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_user;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="reservationEvenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_evenement;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIDEvenement(): ?Evenement
    {
        return $this->ID_evenement;
    }

    public function setIDEvenement(?Evenement $ID_evenement): self
    {
        $this->ID_evenement = $ID_evenement;

        return $this;
    }
}
