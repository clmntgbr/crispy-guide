<?php

namespace App\Service;

use App\Entity\GasStation;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;

class GasStationService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var GooglePlaceApi */
    private $googlePlaceApi;

    public function __construct(EntityManagerInterface $em, GooglePlaceApi $googlePlaceApi)
    {
        $this->em = $em;
        $this->googlePlaceApi = $googlePlaceApi;
    }

    public function update()
    {
        $gasStations = $this->em->getRepository(GasStation::class)->getGasStationsForDetails();

        $client = new Client();

        $response = $client->request("GET", "https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'query' => [
                'rankby' => 'distance',
                'location' => sprintf("%s,%s", 4884700/100000, 241600/100000),
                'type' => 'gas_station',
                'key' => "AIzaSyBeSzCbUb3C0JehfhFtrNgWmlf-7UqtvAw",
            ]
        ]);

        dump($response->getBody()->getContents());
        die;

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody()->getContents(), true);
        }

        foreach ($gasStations as $gasStation) {

        }
    }

    public static function isGasStationClosed(array $element, GasStation $gasStation)
    {
        if (isset($element['fermeture']['attributes']['type']) && "D" == $element['fermeture']['attributes']['type']) {
            $gasStation
                ->setClosedAt(\DateTime::createFromFormat('Y-m-d H:i:s', str_replace("T", " ", substr($element['fermeture']['attributes']['debut'], 0, 19))))
                ->setIsClosed(true)
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
}