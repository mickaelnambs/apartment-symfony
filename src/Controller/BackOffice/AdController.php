<?php

namespace App\Controller\BackOffice;

use App\Constant\PageConstant;
use App\Repository\AdRepository;
use App\Controller\BaseController;
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

    public function new(): Response
    {
        return $this->render("back_office/ad/new.html.twig");
    }
}
