<?php

namespace App\Controller\BackOffice;

use App\Constant\MessageConstant;
use App\Constant\PageConstant;
use App\Repository\AdRepository;
use App\Controller\BaseController;
use App\Entity\Ad;
use App\Form\AdType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdController.
 * 
 * @Route("/admin/ads")
 */
class AdController extends BaseController
{
    private AdRepository $adRepository;

    /**
     * AdController constructor.
     *
     * @param EntityManagerInterface $em
     * @param AdRepository $adRepository
     */
    public function __construct(
        EntityManagerInterface $em, 
        AdRepository $adRepository
    ) {
        parent::__construct($em);
        $this->adRepository = $adRepository;
    }

    /**
     * Permet d'avoir toutes les annonces.
     * 
     * @Route("/", name="admin_ad_index", methods={"GET","POST"})
     *
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $this->adRepository->findAll(),
            $request->query->getInt("page", PageConstant::DEFAULT_PAGE),
            PageConstant::NUM_ITEMS_PER_PAGE
        );
        return $this->render("back_office/ad/index.html.twig", [
            "ads" => $pagination
        ]);
    }

    /**
     * Permet de modifier une annonce.
     * 
     * @Route("/{id}/edit", name="admin_ad_edit", methods={"GET","POST"})
     *
     * @param Ad $ad
     * @param Request $request
     * @return Response
     */
    public function edit(Ad $ad, Request $request): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($ad)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Annonce modifiée avec succès !"
                );
                return $this->redirectToRoute("admin_ad_index");
            }
            return $this->redirectToRoute("admin_ad_edit", ["id" => $ad->getId()]);
        }
        return $this->render("back_office/ad/edit.html.twig", [
            "ad" => $ad,
            "form" => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce.
     * 
     * @Route("/{id}/delete", name="admin_ad_delete")
     *
     * @param Ad $ad
     */
    public function delete(Ad $ad)
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                MessageConstant::ERROR_TYPE,
                "Vous ne pouvez pas supprimer cette annonce, car elle possède déjà des réservations !"
            );
            return $this->redirectToRoute("admin_ad_index");
        } else {
            $this->remove($ad);
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Annonce supprmée avec succès !"
            );
            return $this->redirectToRoute("admin_ad_index");
        }
    }
}
