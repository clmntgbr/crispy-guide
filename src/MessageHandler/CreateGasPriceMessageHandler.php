<?php

namespace App\MessageHandler;

use App\Entity\GasPrice;
use App\Entity\GasStation;
use App\Entity\GasType;
use App\Message\CreateGasPriceMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateGasPriceMessageHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(CreateGasPriceMessage $message)
    {
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }

        $gasStation = $this->em->getRepository(GasStation::class)->findOneBy(['id' => $message->getGasStationId()->getId()]);

        if (null === $gasStation) {
            throw new \Exception(sprintf('Gas Station is null (id: %s', $message->getGasStationId()->getId()));
        }

        $gasType = $this->em->getRepository(GasType::class)->findOneBy(['id' => $message->getGasTypeId()->getId()]);

        if (null === $gasType) {
            throw new \Exception(sprintf('Gas Type is null (id: %s', $message->getGasTypeId()->getId()));
        }

        $gasPrice = new GasPrice();
        $gasPrice
            ->setGasType($gasType)
            ->setGasStation($gasStation)
            ->setDate(\DateTime::createFromFormat('Y-m-d H:i:s', str_replace("T", " ", substr($message->getDate(), 0, 19))))
            ->setDateTimestamp($gasPrice->getDate()->getTimestamp())
            ->setValue((int)str_replace([',', '.'], '', $message->getValue()))
        ;

        $this->em->persist($gasPrice);
        $this->em->flush();

        $lastGasPrices = $gasStation->getLastGasPrices();

        $lastGasPrices[$gasPrice->getGasType()->getId()] = [
            'id' => $gasPrice->getId(),
            'date' => $gasPrice->getDate()->format('Y-m-d H:i:s'),
            'timestamp' => $gasPrice->getDate()->getTimestamp(),
        ];

        $gasStation->setLastGasPrices($lastGasPrices);

        $this->em->persist($gasStation);
        $this->em->flush();
    }
}