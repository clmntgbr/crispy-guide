<?php

namespace App\MessageHandler;

use App\Entity\Address;
use App\Entity\GasStation;
use App\Message\CreateGasStationMessage;
use App\Service\GasStationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateGasStationMessageHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var GasStationService */
    private $gasStationService;

    public function __construct(EntityManagerInterface $em, GasStationService $gasStationService)
    {
        $this->em = $em;
        $this->gasStationService = $gasStationService;
    }

    public function __invoke(CreateGasStationMessage $message)
    {
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }

        $gasStation = $this->em->getRepository(GasStation::class)->findOneBy(['id' => $message->getGasStationId()->getId()]);

        if ($gasStation instanceof GasStation) {
            throw new UnrecoverableMessageHandlingException(sprintf('Gas Station already exist (id : %s)', $message->getGasStationId()->getId()));
        }

        $address = new Address();
        $address
            ->setCity($message->getCity())
            ->setPostalCode($message->getCp())
            ->setLongitude($message->getLongitude())
            ->setLatitude($message->getLatitude())
            ->setCountry($message->getCountry())
            ->setStreet($message->getStreet())
        ;

        $gasStation = new GasStation();
        $gasStation
            ->setId($message->getGasStationId()->getId())
            ->setPop($message->getPop())
            ->setElement($message->getElement())
            ->setAddress($address)
        ;

        GasStationService::isGasStationClosed($message->getElement(), $gasStation);

        $this->gasStationService->getGasStationInformations($gasStation);

        $this->em->persist($gasStation);
        $this->em->flush();
    }
}