<?php

namespace App\Entity;

use App\Repository\EquipementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipementsRepository::class)]
class Equipements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $wifi = null;

    #[ORM\Column]
    private ?bool $projecteur = null;

    #[ORM\Column]
    private ?bool $tableau = null;

    #[ORM\Column]
    private ?bool $prises_electriques = null;

    #[ORM\Column]
    private ?bool $television = null;

    #[ORM\Column]
    private ?bool $climatisation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isWifi(): ?bool
    {
        return $this->wifi;
    }

    public function setWifi(bool $wifi): static
    {
        $this->wifi = $wifi;

        return $this;
    }

    public function isProjecteur(): ?bool
    {
        return $this->projecteur;
    }

    public function setProjecteur(bool $projecteur): static
    {
        $this->projecteur = $projecteur;

        return $this;
    }

    public function isTableau(): ?bool
    {
        return $this->tableau;
    }

    public function setTableau(bool $tableau): static
    {
        $this->tableau = $tableau;

        return $this;
    }

    public function isPrisesElectriques(): ?bool
    {
        return $this->prises_electriques;
    }

    public function setPrisesElectriques(bool $prises_electriques): static
    {
        $this->prises_electriques = $prises_electriques;

        return $this;
    }

    public function isTelevision(): ?bool
    {
        return $this->television;
    }

    public function setTelevision(bool $television): static
    {
        $this->television = $television;

        return $this;
    }

    public function isClimatisation(): ?bool
    {
        return $this->climatisation;
    }

    public function setClimatisation(bool $climatisation): static
    {
        $this->climatisation = $climatisation;

        return $this;
    }
}
