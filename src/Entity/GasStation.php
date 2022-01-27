<?php

namespace App\Entity;

use App\Repository\GasStationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=GasStationRepository::class)
 */
class GasStation
{
    use BlameableEntity;
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
    private $isClosed;

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
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

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

    public function __construct()
    {
        $this->isClosed = false;
        $this->isFoundOnGouvMap = false;
        $this->lastGasPrices = [];
        $this->gasPrices = new ArrayCollection();
        $this->gasServices = new ArrayCollection();
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

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

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
}
