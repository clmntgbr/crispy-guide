<?php

namespace App\Entity;

use App\Repository\GasServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=GasServiceRepository::class)
 */
class GasService
{
    use BlameableEntity;
    use TimestampableEntity;

    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $reference;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=false)
     */
    private $label;

    /**
     * @var GasStation[]
     * @ORM\ManyToMany(targetEntity=GasStation::class, inversedBy="gasServices")
     * @ORM\JoinTable(name="gas_stations_services")
     */
    private $gasStations;

    public function __construct()
    {
        $this->gasStations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|GasStation[]
     */
    public function getGasStations(): Collection
    {
        return $this->gasStations;
    }

    public function addGasStation(GasStation $gasStation): self
    {
        if (!$this->gasStations->contains($gasStation)) {
            $this->gasStations[] = $gasStation;
        }

        return $this;
    }

    public function removeGasStation(GasStation $gasStation): self
    {
        $this->gasStations->removeElement($gasStation);

        return $this;
    }
}
