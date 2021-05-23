<?php

namespace App\Controller\BackOffice;

use App\Service\StatsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DashboardController.
 */
class DashboardController extends AbstractController
{
    /**
     * Permet d'afficher le dashboard.
     * 
     * @Route("/admin", name="dashboard", methods={"GET","POST"})
     *
     * @return Response
     */
    public function index(StatsService $stats): Response
    {
        return $this->render("back_office/dashboard/index.html.twig", [
            "stats" => $stats->getStats(),
            "bestAds" => $stats->getAdsStats("DESC"),
            "worstAds" => $stats->getAdsStats("ASC")
        ]);
    }
}
