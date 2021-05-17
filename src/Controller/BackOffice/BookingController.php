<?php

namespace App\Controller\BackOffice;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController.
 * 
 * @Route("/admin/bookings")
 */
class BookingController extends AbstractController
{
    private BookingRepository $bookingRepository;
}
