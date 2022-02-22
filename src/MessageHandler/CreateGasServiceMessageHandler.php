<?php

namespace App\MessageHandler;

use App\Entity\GasService;
use App\Entity\GasStation;
use App\Message\CreateGasServiceMessage;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateGasServiceMessageHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var Slugify */
    private $slugify;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->slugify = new Slugify();
    }

    public function __invoke(CreateGasServiceMessage $message)
    {
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }

        $gasStation = $this->em->getRepository(GasStation::class)->findOneBy(['id' => $message->getGasStationId()->getId()]);

        if (null === $gasStation) {
            throw new \Exception(sprintf('Gas Station is null (id: %s', $message->getGasStationId()->getId()));
        }

        $gasService = $this->em->getRepository(GasService::class)->findOneBy(['label' => $message->getLabel()]);

        if ($gasService instanceof GasService) {
            if ($gasStation->hasGasService($gasService)) {
                throw new UnrecoverableMessageHandlingException(
                    sprintf('Gas Service is already linked to this Gas Station (Gas Service Label : %s, Gas Station id : %s)', $message->getLabel(), $message->getGasStationId()->getId())
                );
            }
        }

        if (null === $gasService) {
            $gasService = new GasService();
            $gasService
                ->setLabel($message->getLabel())
                ->setReference($this->slugify->slugify($message->getLabel(), '_'))
            ;
        }

        $gasStation->addGasService($gasService);

        $this->em->persist($gasStation);
        $this->em->flush();
    }
}