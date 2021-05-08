<?php

namespace App\Controller\FrontOffice;

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
    public function index(): Response 
    {
        return $this->render('front_office/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
