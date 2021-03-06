<?php

namespace App\Service;

use App\Entity\GasStation;
use GuzzleHttp\Client;

class GooglePlaceApi
{
    const TEXT_SEARCH_URL = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=%s&key=%s&type=gas_station';
    const PLACE_DETAILS_URL = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&key=%s';

    /** @var string */
    private $key;

    /** @var DotEnv */
    private $dotEnv;

    /** @var Client $client */
    private $client;

    public function __construct(DotEnv $dotEnv)
    {
        $this->dotEnv = $dotEnv;
        $this->key = $this->dotEnv->findByParameter('GOOGLE_CLOUD_PLATFORM');
        $this->client = new Client();
    }

    /** @return array|null */
    public function textSearch(GasStation $gasStation)
    {
        $response = $this->client->request("GET", sprintf(self::TEXT_SEARCH_URL, $gasStation->getAddress()->getStreet(), $this->key));
        $response = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('status', $response) && array_key_exists('results', $response) && $response['status'] === 'OK' && count($response['results']) > 0 && array_key_exists('place_id', $response['results'][0])) {
            return $response['results'][0];
        }

        return null;
    }

    /** @return array|null */
    public function placeDetails(GasStation $gasStation)
    {
        $response = $this->client->request("GET", sprintf(self::PLACE_DETAILS_URL, $gasStation->getGooglePlace()->getPlaceId(), $this->key));
        $response = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('status', $response) && array_key_exists('result', $response) && $response['status'] === 'OK' && count($response['result']) > 0) {
            return $response['result'];
        }

        return null;
    }
}