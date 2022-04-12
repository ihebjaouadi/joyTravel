<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_user;

    /**
     * @Assert\NotBlank(message=" titre doit etre non vide")
     * @Assert\Length(
     *      min = 2,
     *      minMessage=" Entrer un titre au mini de 2 caracteres"
     *
     *     )
     * @ORM\Column(type="text", nullable=true)
     */
    private $Body;

    /**
     * @ORM\ManyToOne(targetEntity=Hotel::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ID_hotel;

    /**
     * @ORM\Column(type="smallint")
     */
    private $statue;

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

    public function getBody(): ?string
    {
        return $this->Body;
    }

    public function setBody(string $Body): self
    {
        $this->Body = $Body;

        return $this;
    }

    public function getIDHotel(): ?Hotel
    {
        return $this->ID_hotel;
    }

    public function setIDHotel(Hotel $ID_hotel): self
    {
        $this->ID_hotel = $ID_hotel;

        return $this;
    }

    public function getStatue(): ?int
    {
        return $this->statue;
    }

    public function setStatue(int $statue): self
    {
        $this->statue = $statue;

        return $this;
    }
}
