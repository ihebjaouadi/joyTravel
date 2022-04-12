<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Chambre;
use App\Entity\Hotel;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="remplir tous les champs SVP")
     * @Assert\NotNull(message="{{ value }} shouldn't be NULL")
     * @Assert\LessThan(propertyPath="Date_depart")
     * @Assert\LessThanOrEqual(propertyPath="Date_arrivee")
     */
    private $Date_reservation;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="remplir ce champs SVP")
     * @Assert\LessThan(propertyPath="Date_depart")
     * @Assert\GreaterThanOrEqual(propertyPath="Date_reservation")
     * @Assert\NotNull(message="cette valeur ne doit pas etre NULLE")
     */
    private $Date_arrivee;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="remplir ce champs SVP")
     * @Assert\GreaterThan(propertyPath="Date_arrivee", message="{{ value }} doit etre sup a {{ compared_value }}")
     * @Assert\GreaterThan(propertyPath="Date_reservation")
     */
    private $Date_depart;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="cette valeur ne doit pas etre NULLE")
     * @Assert\NotBlank(message="remplir tous les champs SVP")
     */
    private $ID_user;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="remplir tous les champs SVP")
     * @Assert\NotNull(message="cette valeur ne doit pas etre NULLE")
     */
    private $ID_formule;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="remplir ce champs")
     * @Assert\GreaterThanOrEqual(
     *     value = 0
     * )
     */
    private $Nbr_personnes;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="remplir ce champs")
     * @Assert\GreaterThanOrEqual(
     *     value = 0
     * )
     */
    private $Prix_total;

    /**
     * @Assert\NotBlank(message="Sélectionner des chambres")
     * @Assert\NotNull(message="Sélectionner des chambres")
     * @ORM\ManyToMany(targetEntity=Chambre::class, inversedBy="reservations")
     */
    private $ID_chambre;

    public function __construct()
    {
        $this->ID_chambre = new ArrayCollection();
    }

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

    public function getIDFormule(): ?string
    {
        return $this->ID_formule;
    }

    public function setIDFormule(string $ID_formule): self
    {
        $this->ID_formule = $ID_formule;

        return $this;
    }

    public function getNbrPersonnes(): ?int
    {
        return $this->Nbr_personnes;
    }

    public function setNbrPersonnes(?int $Nbr_personnes): self
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

    /**
     * @return Collection<int, Chambre>
     */
    public function getIDChambre(): Collection
    {
        return $this->ID_chambre;
    }

    public function getNomHotel(): ?string
    {
        $chambres = $this->getIDChambre()->getValues();
//        $values = $this->getIDChambre()->get('1');
//        $chambres = $this->getIDChambre()->toArray();
        dump($chambres);
//        dump($values);
        $names = "";
        foreach ($chambres as $value){
            dump($value);
            $names.=" ".$value->getIDHotel()->getNom()." CH:".$value->getId();
        }
        return $names;
    }

    public function addIDChambre(Chambre $iDChambre): self
    {
        if (!$this->ID_chambre->contains($iDChambre)) {
            $this->ID_chambre[] = $iDChambre;
        }

        return $this;
    }

    public function removeIDChambre(Chambre $iDChambre): self
    {
        $this->ID_chambre->removeElement($iDChambre);

        return $this;
    }
}
