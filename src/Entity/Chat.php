<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $message;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idReceiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getIdSender(): ?User
    {
        return $this->idSender;
    }

    public function setIdSender(?User $idSender): self
    {
        $this->idSender = $idSender;

        return $this;
    }

    public function getIdReceiver(): ?User
    {
        return $this->idReceiver;
    }

    public function setIdReceiver(?User $idReceiver): self
    {
        $this->idReceiver = $idReceiver;

        return $this;
    }
}
