<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\NotBlank(message="Si il vous plait saissir un Nom Valid!!")
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
     *  @Assert\Range(
     *      min = 2,
     *      max = 150,
     *      minMessage = "prix entre doit étre supérieur à  2",
     *      maxMessage = "prix doit étre inférieur à 150"
     * )
     */
    private $Prix;

    /**
     * @ORM\Column(type="integer")
     *   * @Assert\Range(
     *      min = 2,
     *      max = 180,
     *      minMessage = "Le nombre de particant doit étre supérieur à {{ min }} Person !!",
     *      maxMessage = "Le nombre de particant doit étre  iférieur à {{ max }} Person !!"
     * )
     *
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

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryEvent::class, inversedBy="evenements")
     */
    private $Category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Img;

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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getCategory(): ?CategoryEvent
    {
        return $this->Category;
    }

    public function setCategory(?CategoryEvent $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->Img;
    }

    public function setImg(string $Img): self
    {
        $this->Img = $Img;

        return $this;
    }




    public function __toString() {
        return $this->getNom();
    }

}
