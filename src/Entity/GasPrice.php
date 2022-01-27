<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GasPriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=GasPriceRepository::class)
 */
class GasPrice
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
     * @var Currency|null
     * @ORM\ManyToOne(targetEntity=Currency::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $currency;

    /**
     * @var GasType|null
     * @ORM\ManyToOne(targetEntity=GasType::class, cascade={"persist"}, inversedBy="gasPrices")
     * @ORM\JoinColumn(nullable=false)
     */
    public $gasType;

    /**
     * @var GasStation|null
     * @ORM\ManyToOne(targetEntity=GasStation::class, cascade={"persist"}, inversedBy="gasPrices")
     * @ORM\JoinColumn(nullable=false)
     */
    public $gasStation;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $dateTimestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDateTimestamp(): ?int
    {
        return $this->dateTimestamp;
    }

    public function setDateTimestamp(int $dateTimestamp): self
    {
        $this->dateTimestamp = $dateTimestamp;

        return $this;
    }

    public function getGasType(): ?GasType
    {
        return $this->gasType;
    }

    public function setGasType(?GasType $gasType): self
    {
        $this->gasType = $gasType;

        return $this;
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

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
