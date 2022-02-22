<?php

namespace App\MessageHandler;

use App\Entity\GasStation;
use App\Helper\GasStationStatusHelper;
use App\Lists\GasStationStatusReference;
use App\Message\CreateGooglePlaceDetailsMessage;
use App\Service\FileSystem;
use App\Service\GasStationService;
use App\Service\GooglePlaceApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateGooglePlaceDetailsMessageHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var GasStationStatusHelper */
    private $gasStationStatusHelper;

    /** @var GooglePlaceApi */
    private $googlePlaceApi;

    public function __construct(EntityManagerInterface $em, GasStationStatusHelper $gasStationStatusHelper, GooglePlaceApi $googlePlaceApi)
    {
        $this->em = $em;
        $this->gasStationStatusHelper = $gasStationStatusHelper;
        $this->googlePlaceApi = $googlePlaceApi;
    }

    public function __invoke(CreateGooglePlaceDetailsMessage $message)
    {
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }

        $gasStation = $this->em->getRepository(GasStation::class)->findOneBy(['id' => $message->getGasStationId()->getId()]);

        if (null === $gasStation) {
            throw new \Exception(sprintf('Gas Station is null (id: %s', $message->getGasStationId()->getId()));
        }

        if (GasStationStatusReference::PLACE_ID_ANOMALY === $gasStation->getGasStationStatus()->getReference()) {
            return;
        }

        $details = $this->googlePlaceApi->placeDetails($gasStation);

        $gasStation->setName($details['name'] ?? null);

        $this->updateGasStationAddress($gasStation, $details);
        $this->updateGasStationGooglePlace($gasStation, $details);
        $this->getPreview($gasStation);

        $this->gasStationStatusHelper->setStatus(GasStationStatusReference::WAITING_VALIDATION, $gasStation);

        $this->em->flush();
    }

    private function updateGasStationGooglePlace(GasStation $gasStation, array $details)
    {
        $googlePlace = $gasStation->getGooglePlace();

        $googlePlace
            ->setGoogleId($details['id'] ?? null)
            ->setPlaceId($details['place_id'] ?? null)
            ->setBusinessStatus($details['business_status'] ?? null)
            ->setIcon($details['icon'] ?? null)
            ->setPhoneNumber($details['international_phone_number'] ?? null)
            ->setCompoundCode($details['plus_code']['compound_code'] ?? null)
            ->setGlobalCode($details['plus_code']['global_code'] ?? null)
            ->setGoogleRating($details['rating'] ?? null)
            ->setRating($details['rating'] ?? null)
            ->setReference($details['reference'] ?? null)
            ->setOpeningHours($details['opening_hours']['weekday_text'] ?? null)
            ->setUserRatingsTotal($details['user_ratings_total'] ?? null)
            ->setUrl($details['url'] ?? null)
            ->setWebsite($details['website'] ?? null)
        ;

        $this->em->persist($googlePlace);
    }

    private function getPreview(GasStation $gasStation)
    {
        foreach (GasStationService::GAS_STATIONS_IMG as $key => $item) {
            if (false !== strpos(strtolower(FileSystem::stripAccents($gasStation->getName())), $key)) {
                $media = $gasStation->getPreview();
                $media
                    ->setPath(GasStationService::PUBLIC_GAS_STATIONS_IMG)
                    ->setName($item)
                    ->setType('jpg')
                    ->setMimeType('image/jpg')
                    ->setSize(0)
                ;
                $gasStation->setPreview($media);
                return;
            }
        }

    }

    private function updateGasStationAddress(GasStation $gasStation, array $details)
    {
        $address = $gasStation->getAddress();

        foreach ($details['address_components'] as $component) {
            foreach ($component['types'] as $type) {
                switch ($type) {
                    case 'street_number':
                        $address->setNumber($component['long_name']);
                        break;
                    case 'route':
                        $address->setStreet($component['long_name']);
                        break;
                    case 'locality':
                        $address->setCity($component['long_name']);
                        break;
                    case 'administrative_area_level_1':
                        $address->setRegion($component['long_name']);
                        break;
                    case 'country':
                        $address->setCountry($component['long_name']);
                        break;
                    case 'postal_code':
                        $address->setPostalCode($component['long_name']);
                        break;
                }
            }
        }

        $address
            ->setVicinity($details['formatted_address'] ?? null)
            ->setLongitude($details['geometry']['location']['lng'] ?? null)
            ->setLatitude($details['geometry']['location']['lat'] ?? null)
        ;

        $this->em->persist($address);
    }
}