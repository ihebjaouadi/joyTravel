<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
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
    private $Nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Type;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_fin;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nombre_Participants;

    /**
     * @ORM\ManyToOne(targetEntity=Hotel::class, inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_hotel;

    /**
     * @ORM\OneToMany(targetEntity=ReservationEvenement::class, mappedBy="ID_evenement", orphanRemoval=true)
     */
    private $reservationEvenements;

    public function __construct()
    {
        $this->reservationEvenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->Date_debut;
    }

    public function setDateDebut(\DateTimeInterface $Date_debut): self
    {
        $this->Date_debut = $Date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->Date_fin;
    }

    public function setDateFin(\DateTimeInterface $Date_fin): self
    {
        $this->Date_fin = $Date_fin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getNombreParticipants(): ?int
    {
        return $this->Nombre_Participants;
    }

    public function setNombreParticipants(int $Nombre_Participants): self
    {
        $this->Nombre_Participants = $Nombre_Participants;

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

    /**
     * @return Collection<int, ReservationEvenement>
     */
    public function getReservationEvenements(): Collection
    {
        return $this->reservationEvenements;
    }

    public function addReservationEvenement(ReservationEvenement $reservationEvenement): self
    {
        if (!$this->reservationEvenements->contains($reservationEvenement)) {
            $this->reservationEvenements[] = $reservationEvenement;
            $reservationEvenement->setIDEvenement($this);
        }

        return $this;
    }

    public function removeReservationEvenement(ReservationEvenement $reservationEvenement): self
    {
        if ($this->reservationEvenements->removeElement($reservationEvenement)) {
            // set the owning side to null (unless already changed)
            if ($reservationEvenement->getIDEvenement() === $this) {
                $reservationEvenement->setIDEvenement(null);
            }
        }

        return $this;
    }
}
