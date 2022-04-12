<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChambreRepository::class)
 */
class Chambre
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
    private $Type;

    /**
     * @ORM\Column(type="smallint")
     */
    private $Disponibilite;

    /**
     * @ORM\ManyToOne(targetEntity=Hotel::class, inversedBy="chambres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_hotel;

    /**
     * @ORM\OneToMany(targetEntity=Equipement::class, mappedBy="ID_chambre", orphanRemoval=true)
     */
    private $equipements;

    /**
     * @ORM\OneToMany(targetEntity=Disponibilite::class, mappedBy="ID_chambre", orphanRemoval=true)
     */
    private $disponibilites;

    /**
     * @ORM\ManyToMany(targetEntity=Reservation::class, mappedBy="ID_chambre")
     */
    private $reservations;


    public function __construct()
    {
        $this->equipements = new ArrayCollection();
        $this->disponibilites = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDisponibilite(): ?int
    {
        return $this->Disponibilite;
    }

    public function setDisponibilite(int $Disponibilite): self
    {
        $this->Disponibilite = $Disponibilite;

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
     * @return Collection<int, Equipement>
     */
    public function getEquipements(): Collection
    {
        return $this->equipements;
    }

    public function addEquipement(Equipement $equipement): self
    {
        if (!$this->equipements->contains($equipement)) {
            $this->equipements[] = $equipement;
            $equipement->setIDChambre($this);
        }

        return $this;
    }

    public function removeEquipement(Equipement $equipement): self
    {
        if ($this->equipements->removeElement($equipement)) {
            // set the owning side to null (unless already changed)
            if ($equipement->getIDChambre() === $this) {
                $equipement->setIDChambre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Disponibilite>
     */
    public function getDisponibilites(): Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): self
    {
        if (!$this->disponibilites->contains($disponibilite)) {
            $this->disponibilites[] = $disponibilite;
            $disponibilite->setIDChambre($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): self
    {
        if ($this->disponibilites->removeElement($disponibilite)) {
            // set the owning side to null (unless already changed)
            if ($disponibilite->getIDChambre() === $this) {
                $disponibilite->setIDChambre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->addIDChambre($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            $reservation->removeIDChambre($this);
        }

        return $this;
    }

}
