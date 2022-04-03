<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisponibiliteRepository::class)
 */
class Disponibilite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="disponibilites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_chambre;

    /**
     * @ORM\Column(type="date")
     */
    private $reservee_du;

    /**
     * @ORM\Column(type="date")
     */
    private $reservee_au;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIDChambre(): ?Chambre
    {
        return $this->ID_chambre;
    }

    public function setIDChambre(?Chambre $ID_chambre): self
    {
        $this->ID_chambre = $ID_chambre;

        return $this;
    }

    public function getReserveeDu(): ?\DateTimeInterface
    {
        return $this->reservee_du;
    }

    public function setReserveeDu(\DateTimeInterface $reservee_du): self
    {
        $this->reservee_du = $reservee_du;

        return $this;
    }

    public function getReserveeAu(): ?\DateTimeInterface
    {
        return $this->reservee_au;
    }

    public function setReserveeAu(\DateTimeInterface $reservee_au): self
    {
        $this->reservee_au = $reservee_au;

        return $this;
    }
}
