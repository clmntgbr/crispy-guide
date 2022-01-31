<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GasStationStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=GasStationStatusRepository::class)
 */
class GasStationStatus
{
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
}
