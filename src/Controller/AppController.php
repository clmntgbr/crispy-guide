<?php

namespace App\Controller;

use App\Entity\GasStation;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

        return $this->render('app/index.html.twig', [
            'gas_stations' => $gasStations,
        ]);
    }
    /**
     * @Route("/app/{id}", name="app_id")
     * @ParamConverter("gasStation", class=GasStation::class)
     */
    public function indexId(EntityManagerInterface $entityManager, GasStation $gasStation): Response
    {
        return $this->render('app/index_id.html.twig', [
            'gas_station' => $gasStation,
        ]);
    }
}
