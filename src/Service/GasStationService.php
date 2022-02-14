<?php

namespace App\Service;

use App\Entity\GasPrice;
use App\Entity\GasStation;
use App\Entity\GasType;
use App\EntityId\GasStationId;
use App\Helper\GasStationStatusHelper;
use App\Lists\GasStationStatusReference;
use App\Message\CreateGooglePlaceMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;

class GasStationService
{
    const GAS_PRICES_HTML_TEMPLATE = "<p id='%s' style='font-size: 13px;font-family:Raleway, sans-serif;margin: 0;padding: 2px 8px;'><a class='%s %s gas_price' style='color:black;font-family: Raleway-Bold, sans-serif!important;'>%s </a>: <span class='%s gas_price' style='font-family:Raleway-Bold, sans-serif;'>%s €</span>&nbsp;&nbsp;(%s %s)</p>";
    const PUBLIC_GAS_STATIONS_IMG = "img/gas_stations/";
    const GAS_STATIONS_IMG = [
        'total' => 'total.jpg',
        'esso' => 'esso-express.jpg',
        'shell' => 'shell.jpg',
        'bp' => 'bp.jpg',
        'avia' => 'avia.jpg',
        'intermarche' => 'intermarche.jpg',
        'leclerc' => 'leclerc.jpg',
        'carrefour' => 'carrefour.jpg',
        'auchan' => 'auchan.jpg',
    ];

    /** @var EntityManagerInterface */
    private $em;

    /** @var GooglePlaceApi */
    private $googlePlaceApi;

    /** @var MessageBusInterface */
    private $messageBus;

    /** @var RouterInterface */
    private $router;

    /** @var GasStationStatusHelper */
    private $gasStationStatusHelper;

    public function __construct(
        EntityManagerInterface $em,
        GooglePlaceApi $googlePlaceApi,
        GasStationStatusHelper $gasStationStatusHelper,
        MessageBusInterface $messageBus,
        RouterInterface $router
    ) {
        $this->em = $em;
        $this->googlePlaceApi = $googlePlaceApi;
        $this->router = $router;
        $this->gasStationStatusHelper = $gasStationStatusHelper;
        $this->messageBus = $messageBus;
    }

    public function update()
    {
        /** @var GasStation[]|null $gasStations */
        $gasStations = $this->em->getRepository(GasStation::class)->getGasStationsForDetails();

        foreach ($gasStations as $gasStation) {
            $response = $this->googlePlaceApi->textSearch($gasStation);
            if (null === $response) {
                $this->gasStationStatusHelper->setStatus(GasStationStatusReference::NOT_FOUND_IN_TEXTSEARCH, $gasStation);
                continue;
            }

            $this->messageBus->dispatch(new CreateGooglePlaceMessage(
                new GasStationId($gasStation->getId()),
                $response['place_id']
            ));
        }
    }

    public function updateGasStationStatusClosed()
    {
        /** @var GasStation[]|null $gasStations */
        $gasStations = $this->em->getRepository(GasStation::class)->findGasStationStatusClosed();

        foreach ($gasStations as $gasStation) {
            if ($gasStation->getGasPrices()->count() <= 0) {
                $gasStation->setClosedAt(new \DateTime('now'));
                $this->gasStationStatusHelper->setStatus(GasStationStatusReference::CLOSED, $gasStation);
                continue;
            }

            $date = ((new \DateTime('now'))->sub(new \DateInterval('P6M')));
            /** @var GasPrice $lastGasPrice */
            $lastGasPrice = $this->em->getRepository(GasPrice::class)->findLastGasPriceByGasStation($gasStation);
            if ($date > $lastGasPrice->getDate()) {
                $gasStation->setClosedAt($lastGasPrice->getDate());
                $this->gasStationStatusHelper->setStatus(GasStationStatusReference::CLOSED, $gasStation);
            }
        }
    }

    public static function isGasStationClosed(array $element, GasStation $gasStation)
    {
        if (isset($element['fermeture']['attributes']['type']) && "D" == $element['fermeture']['attributes']['type']) {
            $gasStation
                ->setClosedAt(\DateTime::createFromFormat('Y-m-d H:i:s', str_replace("T", " ", substr($element['fermeture']['attributes']['debut'], 0, 19))))
            ;
        }
    }

    public function getGasStationInformations(GasStation $gasStation): void
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf('https://www.prix-carburants.gouv.fr/map/recuperer_infos_pdv/%s', $gasStation->getId()),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "authority: www.prix-carburants.gouv.fr",
                "content-length: 0",
                "accept: text/javascript, text/html, application/xml, text/xml, */*",
                "x-prototype-version: 1.7",
                "x-requested-with: XMLHttpRequest",
                "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36",
                "content-type: application/x-www-form-urlencoded; charset=UTF-8",
                "origin: https://www.prix-carburants.gouv.fr",
                "sec-fetch-site: same-origin",
                "sec-fetch-mode: cors",
                "sec-fetch-dest: empty",
                "referer: https://www.prix-carburants.gouv.fr/",
                "accept-language: fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7,pt;q=0.6,de-DE;q=0.5,de;q=0.4,ru;q=0.3,vi;q=0.2,la;q=0.1,es;q=0.1",
                "cookie: PHPSESSID=74qmi76d5k6vk4uhal69k0qhf6; device_view=full; cookie_law=true; device_view=full"
            ),
        ));

        $html = curl_exec($curl);

        curl_close($curl);

        if(false === $html) {
            return;
        }

        $values = trim(strip_tags(str_replace("\n", '/break/', $html)));
        $values = explode('/break/', $values);
        $values = array_map('trim', $values);
        $values = array_filter($values);

        if (isset($values[5]) && isset($values[6]) && isset($values[7]) && isset($values[8])) {
            $gasStation
                ->setName(trim($values[5]))
                ->setCompany(trim($values[6]))
                ->setIsFoundOnGouvMap(true)
            ;

            $address = $gasStation->getAddress();
            $address
                ->setStreet(sprintf('%s %s', trim($values[7]), trim($values[8])))
            ;
        }
    }

    /**
     * @param string|null $longitude
     * @param string|null $latitude
     * @param string|null $radius
     */
    public function getGasStationForMap($longitude, $latitude, $radius)
    {
        if (is_null($longitude) || is_null($latitude) || is_null($radius)) {
            throw new \Exception('Parameters are missing.');
        }

        $gasStations = $this->em->getRepository(GasStation::class)->getGasStationsForMap($longitude, $latitude, $radius);

        return $this->createPopUpContent($gasStations);
    }

    private function createPopUpContent(array $gasStations)
    {
        foreach ($gasStations as $key => $gasStation) {
            $gasStationIdRoute = $this->router->generate('app_gas_stations_id', ['id' => $gasStation['gas_station_id']]);

            $content = sprintf("<a href='%s' style='font-family:Raleway, sans-serif;z-index:1;margin-bottom:5px;position: relative;width: auto;height: 200px;display: block;background-position: center;background-size: cover;background-image: url(%s%s);'></a>", $gasStationIdRoute, $gasStation['preview_path'], $gasStation['preview_name']);

            $lastGasPrices = json_decode($gasStation['last_gas_prices'], true);
            $previousGasPrices = json_decode($gasStation['previous_gas_prices'], true);

            $gasTypes = $this->em->getRepository(GasType::class)->findAll();

            /** @var GasType $gasType */
            foreach ($gasTypes as $gasType) {
                if (array_key_exists($gasType->getId(), $lastGasPrices)) {
                    if (!array_key_exists($gasType->getId(), $previousGasPrices)) {
                        $content .= $this->createLastGasPricesHtmlTemplate($gasStation, $gasType, $lastGasPrices, 'last_gas_prices_color_green');
                        continue;
                    }

                    if ($lastGasPrices[$gasType->getId()]['price'] > $previousGasPrices[$gasType->getId()]['price']) {
                        $content .= $this->createLastGasPricesHtmlTemplate($gasStation, $gasType, $lastGasPrices, 'last_gas_prices_color_red');
                        continue;
                    }

                    if ($lastGasPrices[$gasType->getId()]['price'] == $previousGasPrices[$gasType->getId()]['price']) {
                        $content .= $this->createLastGasPricesHtmlTemplate($gasStation, $gasType, $lastGasPrices, 'last_gas_prices_color_green');
                        continue;
                    }

                    $content .= $this->createLastGasPricesHtmlTemplate($gasStation, $gasType, $lastGasPrices, 'last_gas_prices_color_orange');
                }
            }

            $content .= sprintf("<a href='%s' style='font-family:Raleway-Bold, sans-serif;font-size: 15px;width: auto;border-radius: 0 0 8px 8px;text-align: center;display: block;margin-top: 10px;background-color: #4f9c49;color: #fff;padding: 13px 0;'>Accèder à la fiche</a>", $gasStationIdRoute);

            $gasStations[$key]['content'] = $content;
        }

        return $gasStations;
    }

    private function createLastGasPricesHtmlTemplate(array $gasStation, GasType $gasType, array $lastGasPrices, string $reference)
    {
        $date = new \DateTime();
        $date->setTimestamp($lastGasPrices[$gasType->getId()]['timestamp']);

        return sprintf(self::GAS_PRICES_HTML_TEMPLATE,
            $lastGasPrices[$gasType->getId()]['id'],
            $gasType->getReference(),
            $reference,
            $gasType->getLabel(),
            $reference,
            $lastGasPrices[$gasType->getId()]['price']/1000,
            'Dernière MAJ le',
            $date->format('d/m/Y')
        );
    }
}