<?php

namespace App\Controller\App;

use App\Entity\GasStation;
use App\Service\DotEnv;
use App\Service\GasStationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GasStationController extends AbstractController
{
    /**
     * @Route("/app/gas_stations", name="app_gas_stations")
     */
    public function gasStations(DotEnv $dotEnv, GasStationService $gasStationService): Response
    {
//        $gasStations = $gasStationService->getGasStationForMap(
//            "2.4016284942626953",
//            "48.794934627541515",
//            "7000"
//        );
//
//        die;

        return $this->render('app/gas_stations.html.twig', [
            'key_map' => $dotEnv->findByParameter('KEY_MAP'),
        ]);
    }

    /**
     * @Route("/app/gas_stations/{id}", name="app_gas_stations_id")
     * @ParamConverter("station", class=GasStation::class, options={"mapping": {"id": "id"}})
     */
    public function gasStationsId(GasStation $gasStation): Response
    {
        return $this->render('app/gas_stations_id.html.twig', []);
    }

    /**
     * @Route("/ajax/gas_stations", name="ajax_gas_stations")
     */
    public function ajaxGasStations(Request $request, GasStationService $gasStationService): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse("This is not an AJAX request.", 400);
        }

        $gasStations = $gasStationService->getGasStationForMap(
            $request->query->get('longitude'),
            $request->query->get('latitude'),
            $request->query->get('radius')
        );

        return new JsonResponse($gasStations, 200);
    }
}
