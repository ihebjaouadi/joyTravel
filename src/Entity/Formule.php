<?php

namespace App\Entity;

use App\Repository\FormuleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormuleRepository::class)
 */
class Formule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Type_chambre;

    /**
     * @ORM\OneToOne(targetEntity=Reservation::class, mappedBy="ID_formule", cascade={"persist", "remove"})
     */
    private $reservation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeChambre(): ?string
    {
        return $this->Type_chambre;
    }

    public function setTypeChambre(string $Type_chambre): self
    {
        $this->Type_chambre = $Type_chambre;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(Reservation $reservation): self
    {
        // set the owning side of the relation if necessary
        if ($reservation->getIDFormule() !== $this) {
            $reservation->setIDFormule($this);
        }

        $this->reservation = $reservation;

        return $this;
    }
}
