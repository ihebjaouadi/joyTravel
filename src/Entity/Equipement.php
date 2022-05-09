<?php

namespace App\Entity;

use App\Repository\EquipementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=EquipementRepository::class)
 */
class Equipement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @Assert\NotBlank(message=" titre doit etre non vide")
     * @Assert\Length(
     *      min = 2,
     *      minMessage=" Entrer un titre au mini de 2 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="equipements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_chambre;

    /**
     * @ORM\ManyToOne(targetEntity=Hotel::class, inversedBy="equipements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_Hotel;

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

    public function getIDChambre(): ?Chambre
    {
        return $this->ID_chambre;
    }

    public function setIDChambre(?Chambre $ID_chambre): self
    {
        $this->ID_chambre = $ID_chambre;

        return $this;
    }

    public function getIDHotel(): ?Hotel
    {
        return $this->ID_Hotel;
    }

    public function setIDHotel(?Hotel $ID_Hotel): self
    {
        $this->ID_Hotel = $ID_Hotel;

        return $this;
    }
}
