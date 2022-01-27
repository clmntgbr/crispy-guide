<?php

namespace App\MessageHandler;

use App\Entity\GasPrice;
use App\Entity\GasService;
use App\Entity\GasStation;
use App\Entity\GasType;
use App\Message\CreateGasPriceMessage;
use App\Message\CreateGasServiceMessage;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
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

        if (null === $gasService) {
            $gasService = new GasService();
            $gasService
                ->setLabel($message->getLabel())
                ->setReference($this->slugify->slugify($message->getLabel()))
            ;
        }

        $gasStation->addGasService($gasService);

        $this->em->persist($gasStation);
        $this->em->flush();
    }
}