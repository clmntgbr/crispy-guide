<?php

namespace App\Service;

use App\Entity\GasService;
use App\Entity\GasStation;
use App\Entity\GasType;
use App\EntityId\GasStationId;
use App\EntityId\GasTypeId;
use App\Message\CreateGasPriceMessage;
use App\Message\CreateGasStationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GasPriceService
{
    const PATH = 'public/gas/instant/';
    const FILENAME = 'gas-prices-instant.zip';

    /** @var EntityManagerInterface */
    private $em;

    /** @var DotEnv */
    private $dotEnv;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(EntityManagerInterface $em, DotEnv $dotEnv, MessageBusInterface $messageBus)
    {
        $this->em = $em;
        $this->dotEnv = $dotEnv;
        $this->messageBus = $messageBus;
    }

    public function update(): void
    {
        $stations = $this->em->getRepository(GasStation::class)->findGasStationById();
        $types = $this->em->getRepository(GasType::class)->findGasTypeById();
        $services = $this->em->getRepository(GasService::class)->findGasServiceByLabel();

        $xmlPath = $this->downloadInstantGasPrices();

        $elements = simplexml_load_file($xmlPath);

        foreach ($elements as $element) {
            $stationId = (string)$element->attributes()->id;

            if (strpos($stationId, '94') === false && strpos($stationId, '94') !== 0) {
                continue;
            }

            if (!array_key_exists($stationId, $stations)) {
                $this->createGasStation($stationId, $element);
                $stations[$stationId] = ["id" => $stationId];
            }

            $this->createGasPrice($stationId, $element, $types);
        }

        FileSystem::delete($xmlPath);
    }

    private function createGasStation(string $stationId, \SimpleXMLElement $element)
    {
        $this->messageBus->dispatch(new CreateGasStationMessage(
            new GasStationId($stationId),
            (string)$element->attributes()->pop,
            (string)$element->attributes()->cp,
            (string)$element->attributes()->longitude,
            (string)$element->attributes()->latitude,
            (string)$element->adresse,
            (string)$element->ville,
            "FRANCE",
            json_decode(str_replace("@", "", json_encode($element)), true)
        ));
    }

    private function createGasPrice(string $stationId, \SimpleXMLElement $element, array $types)
    {
        foreach ($element->prix as $item) {
            $typeId = (string)$item->attributes()->id;

            if (null === $typeId || "" === $typeId) {
                continue;
            }

            $typeId = $types[$typeId]['id'];

            $date = (string)$item->attributes()->maj;

            $date = str_replace("T", " ", substr($date, 0, 19));

            if (null === $date || "" === $date) {
                continue;
            }

            $gasStation = $this->em->getRepository(GasStation::class)->findOneBy(['id' => $stationId]);

            if ($gasStation instanceof GasStation) {
                $lastPrices = $gasStation->getLastGasPrices();
                if (array_key_exists($typeId, $lastPrices)) {
                    if ($lastPrices[$typeId]['date'] >= $date) {
                        continue;
                    }
                }
            }

            $this->messageBus->dispatch(new CreateGasPriceMessage(
                new GasStationId($stationId),
                new GasTypeId($typeId),
                $date,
                (string)$item->attributes()->valeur
            ));
        }
    }

    public function updateGasStationLastGasPrices(GasStation $gasStation)
    {

    }

    private function downloadInstantGasPrices(): string
    {
        FileSystem::delete(self::PATH, self::FILENAME);

        FileSystem::download($this->dotEnv->findByParameter('GAS_URL'), self::FILENAME, self::PATH);

        if (false === FileSystem::exist(self::PATH, self::FILENAME)) {
            throw new \Exception();
        }

        if (false === FileSystem::unzip(sprintf("%s%s", self::PATH, self::FILENAME), self::PATH)) {
            throw new \Exception();
        }

        FileSystem::delete(self::PATH, self::FILENAME);

        if (false === $xmlPath = FileSystem::find(self::PATH, "%\.(xml)$%i")) {
            throw new \Exception();
        }

        return $xmlPath;
    }
}