<?php

namespace App\MessageHandler;

use App\Entity\GasStation;
use App\Entity\GooglePlace;
use App\EntityId\GasStationId;
use App\Helper\GasStationStatusHelper;
use App\Lists\GasStationStatusReference;
use App\Message\CreateGooglePlaceDetailsMessage;
use App\Message\CreateGooglePlaceIdAnomalyMessage;
use App\Message\CreateGooglePlaceMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateGooglePlaceMessageHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var GasStationStatusHelper */
    private $gasStationStatusHelper;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(EntityManagerInterface $em, GasStationStatusHelper $gasStationStatusHelper, MessageBusInterface $messageBus)
    {
        $this->em = $em;
        $this->gasStationStatusHelper = $gasStationStatusHelper;
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateGooglePlaceMessage $message)
    {
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }

        $gasStation = $this->em->getRepository(GasStation::class)->findOneBy(['id' => $message->getGasStationId()->getId()]);

        if (null === $gasStation) {
            throw new \Exception(sprintf('Gas Station is null (id: %s', $message->getGasStationId()->getId()));
        }

        if (GasStationStatusReference::PLACE_ID_ANOMALY === $gasStation->getGasStationStatus()->getLabel()) {
            return;
        }

        $googlePlace = $gasStation->getGooglePlace();

        if (null === $googlePlace) {
            $googlePlace = new GooglePlace();
            $gasStation->setGooglePlace($googlePlace);
        }

        $googlePlace->setPlaceId($message->getPlaceId());

        $gasStationsAnomalies = $this->em->getRepository(GasStation::class)->getGasStationGooglePlaceByPlaceId($message->getPlaceId());

        if (count($gasStationsAnomalies) > 0) {
            $this->createGooglePlaceAnomaly($gasStation, $gasStationsAnomalies);
            return;
        }

        $this->gasStationStatusHelper->setStatus(GasStationStatusReference::FOUND_IN_TEXTSEARCH, $gasStation);

        $this->messageBus->dispatch(new CreateGooglePlaceDetailsMessage(
            new GasStationId($gasStation->getId())
        ));

        $this->em->persist($googlePlace);
        $this->em->persist($gasStation);
        $this->em->flush();
    }

    private function createGooglePlaceAnomaly(GasStation $gasStation, array $gasStations)
    {
        $gasStationIds = [new GasStationId($gasStation->getId())];
        foreach ($gasStations as $station) {
            $gasStationIds[] = new GasStationId($station->getId());
        }

        $this->messageBus->dispatch(new CreateGooglePlaceIdAnomalyMessage(
            $gasStationIds
        ));

        $this->em->persist($gasStation);
        $this->em->flush();
    }
}