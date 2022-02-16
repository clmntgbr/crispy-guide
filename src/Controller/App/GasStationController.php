<?php

namespace App\Controller\App;

use App\Entity\GasStation;
use App\Entity\GasType;
use App\Service\DotEnv;
use App\Service\GasPriceService;
use App\Service\GasStationService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class GasStationController extends AbstractController
{
    /**
     * @Route("/app/gas_stations", name="app_gas_stations")
     */
    public function gasStations(DotEnv $dotEnv, EntityManagerInterface $em, Environment $twig, RouterInterface $router): Response
    {
        return $this->render('app/gas_stations.html.twig', [
            'key_map' => $dotEnv->findByParameter('KEY_MAP'),
            'gas_types' => $em->getRepository(GasType::class)->findGasTypeById(),
        ]);
    }

    /**
     * @Route("/app/gas_stations/{id}", name="app_gas_stations_id")
     * @ParamConverter("station", class=GasStation::class, options={"mapping": {"id": "id"}})
     */
    public function gasStationsId(GasStation $gasStation, EntityManagerInterface $em): Response
    {
        return $this->render('app/gas_stations_id.html.twig', [
            'gasStation' => $gasStation,
            'last_gas_prices' => $gasStation->getLastGasPrices(),
            'previous_gas_prices' => $gasStation->getPreviousGasPrices(),
            'gas_types' => $em->getRepository(GasType::class)->findGasTypeById(),
            'gas_prices_years' => GasPriceService::getGasPricesYears(),
            'year_now' => (new \DateTime('now'))->format('Y'),
            'gas_station_google_map_url' => sprintf("https://maps.google.com/?q=%s", $gasStation->getAddress()->getVicinity()),
        ]);
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
            $request->query->get('radius'),
            $request->query->get('filters')
        );

        return new JsonResponse($gasStations, 200);
    }

    /**
     * @Route("/ajax/gas_station_id/gas_prices", name="ajax_gas_station_id_gas_prices")
     */
    public function ajaxGasStationIdGasPrices(Request $request, GasPriceService $gasPriceService): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse("This is not an AJAX request.", 400);
        }

        $gasPrices = $gasPriceService->getGasPricesByYear(
            $request->query->get('gasStationId'),
            $request->query->get('year')
        );

        return new JsonResponse($gasPrices, 200);
    }
}
