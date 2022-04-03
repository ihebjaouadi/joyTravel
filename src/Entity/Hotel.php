<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HotelRepository::class)
 */
class Hotel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Adresse;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Ville;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Code_postal;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Complement_adresse;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Pays;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Nb_etoile;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="ID_hotel",cascade={"persist","remove"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Chambre::class, mappedBy="ID_hotel", orphanRemoval=true)
     */
    private $chambres;

    /**
     * @ORM\OneToMany(targetEntity=Equipement::class, mappedBy="ID_Hotel", orphanRemoval=true)
     */
    private $equipements;

    /**
     * @ORM\OneToOne(targetEntity=Vote::class, mappedBy="ID_hotel", cascade={"persist", "remove"})
     */
    private $vote;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="ID_hotel", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="ID_hotel", orphanRemoval=true)
     */
    private $evenements;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->chambres = new ArrayCollection();
        $this->equipements = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(?string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(?string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->Code_postal;
    }

    public function setCodePostal(?int $Code_postal): self
    {
        $this->Code_postal = $Code_postal;

        return $this;
    }

    public function getComplementAdresse(): ?string
    {
        return $this->Complement_adresse;
    }

    public function setComplementAdresse(?string $Complement_adresse): self
    {
        $this->Complement_adresse = $Complement_adresse;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(?string $Pays): self
    {
        $this->Pays = $Pays;

        return $this;
    }

    public function getNbEtoile(): ?int
    {
        return $this->Nb_etoile;
    }

    public function setNbEtoile(?int $Nb_etoile): self
    {
        $this->Nb_etoile = $Nb_etoile;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setIDHotel($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getIDHotel() === $this) {
                $image->setIDHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chambre>
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres[] = $chambre;
            $chambre->setIDHotel($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getIDHotel() === $this) {
                $chambre->setIDHotel(null);
            }
        }

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
            $equipement->setIDHotel($this);
        }

        return $this;
    }

    public function removeEquipement(Equipement $equipement): self
    {
        if ($this->equipements->removeElement($equipement)) {
            // set the owning side to null (unless already changed)
            if ($equipement->getIDHotel() === $this) {
                $equipement->setIDHotel(null);
            }
        }

        return $this;
    }

    public function getVote(): ?Vote
    {
        return $this->vote;
    }

    public function setVote(Vote $vote): self
    {
        // set the owning side of the relation if necessary
        if ($vote->getIDHotel() !== $this) {
            $vote->setIDHotel($this);
        }

        $this->vote = $vote;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIDHotel($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIDHotel() === $this) {
                $commentaire->setIDHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setIDHotel($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getIDHotel() === $this) {
                $evenement->setIDHotel(null);
            }
        }

        return $this;
    }
}
