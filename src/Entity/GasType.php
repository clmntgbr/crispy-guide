<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GasTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=GasTypeRepository::class)
 */
class GasType
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
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $reference;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=false)
     */
    private $label;

    /**
     * @var GasPrice[]
     * @ORM\OneToMany(targetEntity=GasPrice::class, mappedBy="gasType", cascade={"persist", "remove"})
     */
    private $gasPrices;

    public function __construct()
    {
        $this->gasPrices = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLabel();
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
            $gasPrice->setGasType($this);
        }

        return $this;
    }

    public function removeGasPrice(GasPrice $gasPrice): self
    {
        if ($this->gasPrices->removeElement($gasPrice)) {
            // set the owning side to null (unless already changed)
            if ($gasPrice->getGasType() === $this) {
                $gasPrice->setGasType(null);
            }
        }

        return $this;
    }
}
