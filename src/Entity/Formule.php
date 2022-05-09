<?php

namespace App\Entity;

use App\Repository\FormuleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotNull(message="cette valeur ne doit pas etre NULLE")
     */
    private $Type_chambre;


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
}
