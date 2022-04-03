<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
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
    private $Date_reservation;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_arrivee;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_depart;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_user;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_chambre;

    /**
     * @ORM\OneToOne(targetEntity=Formule::class, inversedBy="reservation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_formule;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nbr_personnes;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix_total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->Date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $Date_reservation): self
    {
        $this->Date_reservation = $Date_reservation;

        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->Date_arrivee;
    }

    public function setDateArrivee(\DateTimeInterface $Date_arrivee): self
    {
        $this->Date_arrivee = $Date_arrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->Date_depart;
    }

    public function setDateDepart(\DateTimeInterface $Date_depart): self
    {
        $this->Date_depart = $Date_depart;

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

    public function getIDChambre(): ?Chambre
    {
        return $this->ID_chambre;
    }

    public function setIDChambre(?Chambre $ID_chambre): self
    {
        $this->ID_chambre = $ID_chambre;

        return $this;
    }

    public function getIDFormule(): ?Formule
    {
        return $this->ID_formule;
    }

    public function setIDFormule(Formule $ID_formule): self
    {
        $this->ID_formule = $ID_formule;

        return $this;
    }

    public function getNbrPersonnes(): ?int
    {
        return $this->Nbr_personnes;
    }

    public function setNbrPersonnes(int $Nbr_personnes): self
    {
        $this->Nbr_personnes = $Nbr_personnes;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->Prix_total;
    }

    public function setPrixTotal(float $Prix_total): self
    {
        $this->Prix_total = $Prix_total;

        return $this;
    }
}
