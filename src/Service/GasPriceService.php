<?php

namespace App\Service;

use App\Entity\Currency;
use App\Entity\GasStation;
use App\Entity\GasType;
use App\EntityId\GasStationId;
use App\EntityId\GasTypeId;
use App\Lists\CurrencyReference;
use App\Message\CreateGasPriceMessage;
use App\Message\CreateGasServiceMessage;
use App\Message\CreateGasStationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

class GasPriceService
{
    const PATH = 'public/gas_file/';
    const FILENAME = 'gas-prices.zip';
    const YEAR_BEGIN = 2007;
    const YEAR_END = 2010;

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

    public function updateInstantGasPrices(): void
    {
        $gasStations = $this->em->getRepository(GasStation::class)->findGasStationById();
        $gasServices = $this->em->getRepository(GasStation::class)->findGasServiceByGasStationId();
        $types = $this->em->getRepository(GasType::class)->findGasTypeById();

        $xmlPath = $this->downloadInstantGasPrices();

        $elements = simplexml_load_file($xmlPath);

        foreach ($elements as $element) {
            $stationId = (string)$element->attributes()->id;

            if (strpos($stationId, '94') !== 0) {
                continue;
            }

            if (!array_key_exists($stationId, $gasStations)) {
                $this->createGasStation($stationId, $element);
                $gasStations[$stationId] = ["id" => $stationId];
            }

            $this->createGasService($stationId, $element, $gasServices);
            $this->createGasPrice($stationId, $element, $types);
        }

        FileSystem::delete($xmlPath);
    }

    public function updateYearGasPrices()
    {
        $gasStations = $this->em->getRepository(GasStation::class)->findGasStationById();
        $gasServices = $this->em->getRepository(GasStation::class)->findGasServiceByGasStationId();
        $types = $this->em->getRepository(GasType::class)->findGasTypeById();

        for ($i=self::YEAR_BEGIN;$i<=self::YEAR_END;$i++) {

            dump(sprintf("%s ...", $i));

            $xmlPath = $this->downloadYearGasPrices($i);

            $elements = simplexml_load_file($xmlPath);

            foreach ($elements as $element) {
                $stationId = (string)$element->attributes()->id;

//                if (strpos($stationId, '94') !== 0) {
//                    continue;
//                }

                if (!array_key_exists($stationId, $gasStations)) {
                    $this->createGasStation($stationId, $element);
                    $gasStations[$stationId] = ["id" => $stationId];
                }

                $this->createGasService($stationId, $element, $gasServices);
                $this->createYearGasPrice($stationId, $element, $types, $i);
            }

            FileSystem::delete($xmlPath);
        }
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

    private function createGasService(string $stationId, \SimpleXMLElement $element, array $gasServices)
    {
        foreach ((array)$element->services->service as $item) {
            if (array_key_exists($stationId, $gasServices)) {
                if (array_key_exists($item, $gasServices[$stationId])) {
                    continue;
                }
            }

            $this->messageBus->dispatch(new CreateGasServiceMessage(
                new GasStationId($stationId),
                $item
            ));
        }
    }

    private function createYearGasPrice(string $stationId, \SimpleXMLElement $element, array $types, string $year)
    {
        $currency = $this->em->getRepository(Currency::class)->findOneBy(['reference' => CurrencyReference::EUR]);

        if (null === $currency) {
            throw new UnrecoverableMessageHandlingException('Currency is null (reference: eur)');
        }

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

            FileSystem::createDirectoryIfDontExist(sprintf('public/sql/gas_prices/%s', $year));

            $date = \DateTime::createFromFormat('Y-m-d H:i:s', str_replace("T", " ", substr($date, 0, 19)));

            $query = sprintf("INSERT INTO gas_price (gas_type_id, gas_station_id, value, date, date_timestamp, created_at, updated_at, currency_id) VALUES (%s, %s, %s, '%s', %s, '%s', '%s', %s);%s",
                $typeId, $stationId, (int)str_replace([',', '.'], '',
                (string)$item->attributes()->valeur), $date->format('Y-m-d H:i:s'), $date->getTimestamp(),
                (new \DateTime('now'))->format('Y-m-d H:i:s'),
                (new \DateTime('now'))->format('Y-m-d H:i:s'),
                $currency->getId(),
                PHP_EOL
            );

            file_put_contents(sprintf('public/sql/gas_prices/%s/%s', $year, $stationId), $query ,FILE_APPEND);
        }
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

    private function downloadYearGasPrices(string $year): string
    {
        FileSystem::delete(self::PATH, self::FILENAME);

        FileSystem::download(sprintf($this->dotEnv->findByParameter('GAS_URL_YEAR'), $year), self::FILENAME, self::PATH);

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