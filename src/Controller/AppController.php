<?php

namespace App\Controller;

use App\Entity\GasStation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
   public function index(EntityManagerInterface $entityManager): Response
    {
        $gasStations = $entityManager->getRepository(GasStation::class)->findAll();

        foreach ($gasStations as $gasStation) {
            dump(
                $gasStation->getId(),
                $gasStation->getGasStationStatus()->getLabel(),
                $gasStation->getAddress()->getStreet(),
                $gasStation->getGooglePlace() ? $gasStation->getGooglePlace()->getPlaceId() : null,
                "========================================="
            );
        }
        die;
        return $this->render('app/index.html.twig', [
            'gas_stations' => $gasStations,
        ]);
    }
}
