<?php

namespace App\Controller\FrontOffice;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AdRepository $adRepository, UserRepository $userRepository): Response 
    {
        return $this->render('front_office/home/index.html.twig', [
            "ads" => $adRepository->findBestAds(3),
            "users" => $userRepository->findBestUsers(2)
        ]);
    }
}
