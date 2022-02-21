<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 *     normalizationContext={"groups"={"read"}}
 * )
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
class Media
{
    use TimestampableEntity;

    /** @var null|UploadedFile */
    private $file;

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
     * @ORM\Column(name="path", type="string", nullable=true)
     * @Groups({"read"})
     */
    private $path;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     * @Groups({"read"})
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(name="mime_type", type="string", nullable=true)
     * @Groups({"read"})
     */
    private $mimeType;

    /**
     * @var string|null
     * @ORM\Column(name="type", type="string", nullable=true)
     * @Groups({"read"})
     */
    private $type;

    /**
     * @var float|null
     * @ORM\Column(name="size", type="decimal", nullable=true)
     * @Groups({"read"})
     */
    private $size;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }
}
