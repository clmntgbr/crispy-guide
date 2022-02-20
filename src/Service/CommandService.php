<?php

namespace App\Service;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;

class CommandService
{
    /** @var Command */
    private $command;

    /** @var EntityManagerInterface */
    private $em;

    private $message;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->message = [];
    }

    public function start(string $name): Command
    {
        $this->command = new Command();
        $this->command->setCommand($name);
        $this->command->setStartDate(new \DateTime());

        return $this->command;
    }

    public function addMessageIteration($key)
    {
        if (array_key_exists($key, $this->message)) {
            $this->message[$key]++;
            return;
        }

        $this->message[$key] = 0;
    }

    public function end(): void
    {
        $this->command->setEndDate(new \DateTime());
        $this->command->setMessage($this->message);

        $this->em->persist($this->command);
        $this->em->flush();
    }
}