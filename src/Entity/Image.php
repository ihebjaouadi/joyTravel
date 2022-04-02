<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Chemain;

    /**
     * @ORM\ManyToOne(targetEntity=hotel::class, inversedBy="images")
     */
    private $ID_hotel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChemain(): ?string
    {
        return $this->Chemain;
    }

    public function setChemain(string $Chemain): self
    {
        $this->Chemain = $Chemain;

        return $this;
    }

    public function getIDHotel(): ?hotel
    {
        return $this->ID_hotel;
    }

    public function setIDHotel(?hotel $ID_hotel): self
    {
        $this->ID_hotel = $ID_hotel;

        return $this;
    }
}
