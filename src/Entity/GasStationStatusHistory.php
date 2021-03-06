<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GasStationStatusHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 *     normalizationContext={"groups"={"read"}}
 * )
 * @ORM\Entity(repositoryClass=GasStationStatusHistoryRepository::class)
 */
class GasStationStatusHistory
{
    use TimestampableEntity;

    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var GasStation|null
     * @ORM\ManyToOne(targetEntity=GasStation::class, inversedBy="gasStationStatusHistories", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    public $gasStation;

    /**
     * @var GasStationStatus|null
     * @ORM\ManyToOne(targetEntity=GasStationStatus::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    public $gasStationStatus;

    public function __toString()
    {
        return $this->gasStationStatus->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGasStation(): ?GasStation
    {
        return $this->gasStation;
    }

    public function setGasStation(?GasStation $gasStation): self
    {
        $this->gasStation = $gasStation;

        return $this;
    }

    public function getGasStationStatus(): ?GasStationStatus
    {
        return $this->gasStationStatus;
    }

    public function setGasStationStatus(?GasStationStatus $gasStationStatus): self
    {
        $this->gasStationStatus = $gasStationStatus;

        return $this;
    }
}
