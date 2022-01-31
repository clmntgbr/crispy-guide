<?php

namespace App\Controller\Admin;

use App\Entity\Currency;
use App\Entity\GasPrice;
use App\Entity\GasStation;
use App\Entity\GasType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Crispy Guide');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Api Docs', 'fas fa-map-marker-alt', '/api/docs');
        yield MenuItem::linkToCrud('Gas Stations', 'fas fa-map-marker-alt', GasStation::class);
        yield MenuItem::linkToCrud('Gas Types', 'fas fa-map-marker-alt', GasType::class);
        yield MenuItem::linkToCrud('Gas Prices', 'fas fa-map-marker-alt', GasPrice::class);
        yield MenuItem::linkToCrud('Currency', 'fas fa-map-marker-alt', Currency::class);
    }
}
