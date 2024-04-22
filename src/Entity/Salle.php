<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $capacite = null;

    /**
     * @var Collection<int, Equipement>
     */
    #[ORM\ManyToMany(targetEntity: Equipement::class, inversedBy: 'salles')]
    private Collection $equipement;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'salle', orphanRemoval: true)]
    private Collection $reservation;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'salle')]
    private Collection $reservations;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'salle')]
    private Collection $users;

    /**
     * @var Collection<int, RoomRating>
     */
    #[ORM\OneToMany(targetEntity: RoomRating::class, mappedBy: 'room')]
    private Collection $roomRatings;



    public function __construct()
    {
        $this->equipement = new ArrayCollection();
        $this->reservation = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->roomRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * @return Collection<int, Equipement>
     */
    public function getEquipement(): Collection
    {
        return $this->equipement;
    }

    public function addEquipement(Equipement $equipement): static
    {
        if (!$this->equipement->contains($equipement)) {
            $this->equipement->add($equipement);
        }

        return $this;
    }

    public function removeEquipement(Equipement $equipement): static
    {
        $this->equipement->removeElement($equipement);

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setSalle($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSalle() === $this) {
                $reservation->setSalle(null);
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

    /**
     * @return Collection<int, Reservation>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Reservation $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSalle($this);
        }

        return $this;
    }

    public function removeUser(Reservation $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSalle() === $this) {
                $user->setSalle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RoomRating>
     */
    public function getRoomRatings(): Collection
    {
        return $this->roomRatings;
    }

    public function addRoomRating(RoomRating $roomRating): static
    {
        if (!$this->roomRatings->contains($roomRating)) {
            $this->roomRatings->add($roomRating);
            $roomRating->setRoom($this);
        }

        return $this;
    }

    public function removeRoomRating(RoomRating $roomRating): static
    {
        if ($this->roomRatings->removeElement($roomRating)) {
            // set the owning side to null (unless already changed)
            if ($roomRating->getRoom() === $this) {
                $roomRating->setRoom(null);
            }
        }

        return $this;
    }

    
}
