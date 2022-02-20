<?php

namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=CommandRepository::class)
 */
class Command
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
     * @ORM\Column(type="string", nullable=false)
     */
    private $command;

    /**
     * @var array
     * @ORM\Column(type="json", nullable=true)
     */
    private $message;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    public function adminMessage()
    {
        $data = [];
        foreach ($this->message ?? [] as $key => $item) {
            $data[] = sprintf('%s : %s', $key, $item);
        }

        return $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getMessage(): ?array
    {
        return $this->message;
    }

    public function setMessage(?array $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
}
