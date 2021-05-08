<?php

namespace App\Controller\FrontOffice;

use App\Constant\MessageConstant;
use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class BookingController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class BookingController extends BaseController
{
    /**
     * BookingController constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    /**
     * Permet de reserver un appartement.
     * 
     * @Route("/ads/{slug}/book", name="booking_new", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_USER")
     *
     * @param Ad $ad
     * @param Request $request
     * @return Response
     */
    public function book(Ad $ad, Request $request): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setBooker($this->getUser())
                ->setAd($ad);

            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    MessageConstant::WARNING_TYPE,
                    "Les dates que vous avez choisi ne peuvent être réservées : elles sont déjà prises."
                );
            } else {
                $this->save($booking);
                return $this->redirectToRoute(
                    'booking_show', 
                    [
                        'id' => $booking->getId(),
                        'withAlert' => true
                    ]
                );
            }
        }
        return $this->render("front_office/booking/book.html.twig", [
            "ad" => $ad,
            "form" => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une reservation.
     * 
     * @Route("/booking/{id}", name="booking_show")
     * 
     * @IsGranted("ROLE_USER")
     *
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking): Response
    {
        return $this->render("front_office/booking/show.html.twig", [
            "booking" => $booking
        ]);
    }
}