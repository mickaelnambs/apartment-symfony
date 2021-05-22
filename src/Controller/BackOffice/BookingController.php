<?php

namespace App\Controller\BackOffice;

use App\Constant\MessageConstant;
use App\Constant\PageConstant;
use App\Controller\BaseController;
use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController.
 * 
 * @Route("/admin/bookings")
 */
class BookingController extends BaseController
{
    private BookingRepository $bookingRepository;

    /**
     * BookingController constructor.
     *
     * @param EntityManagerInterface $em
     * @param BookingRepository $bookingRepository
     */
    public function __construct(
        EntityManagerInterface $em, 
        BookingRepository $bookingRepository
    ) {
        parent::__construct($em);
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Permet d'avoir tous les bookings.
     * 
     * @Route("/", name="admin_booking_index", methods={"GET","POST"})
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $this->bookingRepository->findAll(),
            $request->query->getInt("page", PageConstant::DEFAULT_PAGE),
            PageConstant::NUM_ITEMS_PER_PAGE
        );
        return $this->render("back_office/booking/index.html.twig", [
            "bookings" => $pagination
        ]);
    }

    /**
     * Permet de modifier une booking.
     * 
     * @Route("/{id}/edit", name="admin_booking_edit", methods={"GET","POST"})
     * 
     * @param Booking $booking
     * @param Request $request
     * @return Response
     */
    public function edit(Booking $booking, Request $request): Response
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($booking)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Réservation modifiée avec succès"
                );
                return $this->redirectToRoute("admin_booking_index");
            }
            $this->addFlash(
                MessageConstant::ERROR_TYPE,
                "Il y a une erreur lors de la modification !"
            );
            return $this->redirectToRoute("admin_booking_edit", ["id" => $booking->getId()]);
        }
        return $this->render("back_office/booking/edit.html.twig", [
            "form" => $form->createView(),
            "booking" => $booking
        ]);
    }

    /**
     * Permet de supprimer un booking.
     * 
     * @Route("/{id}/delete", name="admin_booking_delete")
     *
     * @param Booking $booking
     * @return Response
     */
    public function delete(Booking $booking): Response
    {
        if ($this->remove($booking)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Réservation supprimée avec succès"
            );
            return $this->redirectToRoute("admin_booking_index");
        }
    }

}
