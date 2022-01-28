<?php

namespace App\MessageHandler;

use App\Entity\GasStation;
use App\Helper\GasStationStatusHelper;
use App\Lists\GasStationStatusReference;
use App\Message\CreateGooglePlaceIdAnomalyMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateGooglePlaceIdAnomalyMessageHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var GasStationStatusHelper */
    private $gasStationStatusHelper;

    public function __construct(EntityManagerInterface $em, GasStationStatusHelper $gasStationStatusHelper)
    {
        $this->em = $em;
        $this->gasStationStatusHelper = $gasStationStatusHelper;
    }

    public function __invoke(CreateGooglePlaceIdAnomalyMessage $message)
    {
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }

        foreach ($message->getGasStationIds() as $gasStationId) {
            $gasStation = $this->em->getRepository(GasStation::class)->findOneBy(['id' => $gasStationId->getId()]);

            if (null === $gasStation) {
                throw new \Exception(sprintf('Gas Station is null (id: %s', $gasStationId->getId()));
            }

            $this->gasStationStatusHelper->setStatus(GasStationStatusReference::PLACE_ID_ANOMALY, $gasStation);
        }
    }
}