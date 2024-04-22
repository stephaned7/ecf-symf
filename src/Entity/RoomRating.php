<?php

namespace App\Entity;

use App\Repository\RoomRatingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRatingRepository::class)]
class RoomRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\ManyToOne(inversedBy: 'client')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Posted_At = null;

    #[ORM\ManyToOne(inversedBy: 'roomRatings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salle $Room = null;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeImmutable
    {
        return $this->Posted_At;
    }

    public function setPostedAt(\DateTimeImmutable $Posted_At): static
    {
        $this->Posted_At = $Posted_At;

        return $this;
    }

    public function getRoom(): ?salle
    {
        return $this->Room;
    }

    public function setRoom(?salle $Room): static
    {
        $this->Room = $Room;

        return $this;
    }
}
