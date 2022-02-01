<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GasStationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=GasStationRepository::class)
 */
class GasStation
{
    use TimestampableEntity;

    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $pop;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $company;

    /**
     * @var array|null
     * @ORM\Column(type="array")
     */
    private $element;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isFoundOnGouvMap;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closedAt;

    /**
     * @var Address|null
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @var GooglePlace|null
     * @ORM\OneToOne(targetEntity=GooglePlace::class, cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="google_place_id", referencedColumnName="id")
     */
    private $googlePlace;

    /**
     * @var GasStationStatus|null
     * @ORM\ManyToOne(targetEntity=GasStationStatus::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $gasStationStatus;

    /**
     * @var GasPrice[]
     * @ORM\OneToMany(targetEntity=GasPrice::class, mappedBy="gasStation", cascade={"persist", "remove"}, fetch="LAZY")
     * @ORM\OrderBy({"date" = "ASC", "gasType" = "ASC"})
     */
    private $gasPrices;

    /**
     * @var array
     * @ORM\Column(type="json", nullable=true)
     */
    private $lastGasPrices;

    /**
     * @var GasService[]
     * @ORM\ManyToMany(targetEntity=GasService::class, mappedBy="gasStations", cascade={"persist"})
     */
    private $gasServices;

    /**
     * @var GasStationStatusHistory[]
     * @ORM\OneToMany(targetEntity=GasStationStatusHistory::class, mappedBy="gasStation", cascade={"persist"})
     */
    private $gasStationStatusHistories;

    public function __construct()
    {
        $this->isFoundOnGouvMap = false;
        $this->lastGasPrices = [];
        $this->gasPrices = new ArrayCollection();
        $this->gasServices = new ArrayCollection();
        $this->gasStationStatusHistories = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|GasPrice[]
     */
    public function getGasPrices(): Collection
    {
        return $this->gasPrices;
    }

    public function getCountGasPrices(): int
    {
        return $this->gasPrices->count();
    }

    public function addGasPrice(GasPrice $gasPrice): self
    {
        if (!$this->gasPrices->contains($gasPrice)) {
            $this->gasPrices[] = $gasPrice;
            $gasPrice->setGasStation($this);
        }

        return $this;
    }

    public function removeGasPrice(GasPrice $gasPrice): self
    {
        if ($this->gasPrices->removeElement($gasPrice)) {
            // set the owning side to null (unless already changed)
            if ($gasPrice->getGasStation() === $this) {
                $gasPrice->setGasStation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GasService[]
     */
    public function getGasServices(): Collection
    {
        return $this->gasServices;
    }

    public function addGasService(GasService $gasService): self
    {
        if (!$this->gasServices->contains($gasService)) {
            $this->gasServices[] = $gasService;
            $gasService->addGasStation($this);
        }

        return $this;
    }

    public function hasGasService(GasService $gasService): bool
    {
        return $this->gasServices->contains($gasService);
    }

    public function removeGasService(GasService $gasService): self
    {
        if ($this->gasServices->removeElement($gasService)) {
            $gasService->removeGasStation($this);
        }

        return $this;
    }

    public function getPop(): ?string
    {
        return $this->pop;
    }

    public function setPop(string $pop): self
    {
        $this->pop = $pop;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getElement(): ?array
    {
        return $this->element;
    }

    public function setElement(array $element): self
    {
        $this->element = $element;

        return $this;
    }

    public function getClosedAt(): ?\DateTimeInterface
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeInterface $closedAt): self
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getIsFoundOnGouvMap(): ?bool
    {
        return $this->isFoundOnGouvMap;
    }

    public function setIsFoundOnGouvMap(bool $isFoundOnGouvMap): self
    {
        $this->isFoundOnGouvMap = $isFoundOnGouvMap;

        return $this;
    }

    public function getLastGasPrices()
    {
        return $this->lastGasPrices;
    }

    public function setLastGasPrices(?array $lastGasPrices): self
    {
        $this->lastGasPrices = $lastGasPrices;

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

    public function getGooglePlace(): ?GooglePlace
    {
        return $this->googlePlace;
    }

    public function setGooglePlace(?GooglePlace $googlePlace): self
    {
        $this->googlePlace = $googlePlace;

        return $this;
    }

    /**
     * @return Collection|GasStationStatusHistory[]
     */
    public function getGasStationStatusHistories(): Collection
    {
        return $this->gasStationStatusHistories;
    }

    public function getPreviousGasStationStatusHistory(): GasStationStatusHistory
    {
        $lastGasStationStatusHistory = $this->gasStationStatusHistories->last();
        $previousGasStationStatusHistory = null;

        foreach ($this->gasStationStatusHistories as $gasStationStatusHistory) {
            if ($gasStationStatusHistory->getId() !== $lastGasStationStatusHistory->getId()) {
                $previousGasStationStatusHistory = $gasStationStatusHistory;
            }
        }

        if (null === $previousGasStationStatusHistory) {
            return $lastGasStationStatusHistory;
        }

        return $previousGasStationStatusHistory;
    }

    public function addGasStationStatusHistory(GasStationStatusHistory $gasStationStatusHistory): self
    {
        if (!$this->gasStationStatusHistories->contains($gasStationStatusHistory)) {
            $this->gasStationStatusHistories[] = $gasStationStatusHistory;
        }

        return $this;
    }

    public function removeGasStationStatusHistory(GasStationStatusHistory $gasStationStatusHistory): self
    {
        return $this;
    }
}
