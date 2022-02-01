<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GooglePlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GooglePlaceRepository::class)
 */
class GooglePlace
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleId;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $website;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $placeId;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $compoundCode;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $globalCode;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleRating;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $rating;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $userRatingsTotal;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $businessStatus;

    /**
     * @var array|null
     * @ORM\Column(type="array", nullable=true)
     */
    private $openingHours;

    public function __toString()
    {
        if (null === $this->placeId) {
            return (string)$this->id;
        }
        return (string)$this->placeId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPlaceId(): ?string
    {
        return $this->placeId;
    }

    public function setPlaceId(?string $placeId): self
    {
        $this->placeId = $placeId;

        return $this;
    }

    public function getCompoundCode(): ?string
    {
        return $this->compoundCode;
    }

    public function setCompoundCode(?string $compoundCode): self
    {
        $this->compoundCode = $compoundCode;

        return $this;
    }

    public function getGlobalCode(): ?string
    {
        return $this->globalCode;
    }

    public function setGlobalCode(?string $globalCode): self
    {
        $this->globalCode = $globalCode;

        return $this;
    }

    public function getGoogleRating(): ?string
    {
        return $this->googleRating;
    }

    public function setGoogleRating(?string $googleRating): self
    {
        $this->googleRating = $googleRating;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getUserRatingsTotal(): ?string
    {
        return $this->userRatingsTotal;
    }

    public function setUserRatingsTotal(?string $userRatingsTotal): self
    {
        $this->userRatingsTotal = $userRatingsTotal;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getBusinessStatus(): ?string
    {
        return $this->businessStatus;
    }

    public function setBusinessStatus(?string $businessStatus): self
    {
        $this->businessStatus = $businessStatus;

        return $this;
    }

    public function getOpeningHours(): ?array
    {
        return $this->openingHours;
    }

    public function setOpeningHours(?array $openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }
}
