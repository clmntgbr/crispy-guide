<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 *     normalizationContext={"groups"={"read"}}
 * )
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
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
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $vicinity;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Groups({"read"})
     */
    private $street;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $number;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Groups({"read"})
     */
    private $city;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $region;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Groups({"read"})
     */
    private $postalCode;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Groups({"read"})
     */
    private $country;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $longitude;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $latitude;

    public function __toString()
    {
        return (string)$this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVicinity(): ?string
    {
        return $this->vicinity;
    }

    public function setVicinity(?string $vicinity): self
    {
        $this->vicinity = $vicinity;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }
}
