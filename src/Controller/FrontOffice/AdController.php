<?php

namespace App\Controller\FrontOffice;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Constant\MessageConstant;
use App\Constant\PageConstant;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdController.
 * 
 * @Route("/ads")
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
    public function __construct(EntityManagerInterface $em, AdRepository $adRepository)
    {
        parent::__construct($em);
        $this->adRepository = $adRepository;
    }

    /**
     * Get all ads.
     * 
     * @Route("/", name="ad_index", methods={"GET","POST"})
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $this->adRepository->findAll(),
            $request->query->get("page", PageConstant::DEFAULT_PAGE),
            PageConstant::NUM_ITEMS_PER_PAGE
        );
        return $this->render("front_office/ad/index.html.twig", [
            "ads" => $pagination
        ]);
    }


    /**
     * Create new Ad.
     * 
     * @Route("/new", name="ad_new", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad->setAuthor($this->getUser());
            if ($this->save($ad)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Ad created successfully !"
                );
                return $this->redirectToRoute("ad_show", [
                    'slug' => $ad->getSlug(),
                    'id' => $ad->getId()
                ]);
            }
            return $this->redirectToRoute("ad_new");
        }
        return $this->render("front_office/ad/new.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * Get one ad.
     * 
     * @Route("/{slug}-{id}", name="ad_show", requirements={"slug": "[a-z0-9\-]*"})
     *
     * @param Ad $ad
     * @param string $slug
     * @return Response
     */
    public function show(Ad $ad, string $slug): Response
    {
        if ($ad->getSlug() !== $slug) {
            return $this->redirectToRoute("ad_show", [
                "slug" => $ad->getSlug(),
                "id" => $ad->getId()
            ]);
        }
        return $this->render('front_office/ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * Edit Ad.
     * 
     * @Route("/{slug}/edit", name="ad_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifer !")
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
            $ad->setAuthor($this->getUser());
            if ($this->save($ad)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Ad updated successfully !"
                );
                return $this->redirectToRoute("ad_show", [
                    'slug' => $ad->getSlug(),
                    'id' => $ad->getId()
                ]);
            }
            return $this->redirectToRoute("ad_show", [
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render("front_office/ad/edit.html.twig", [
            "ad" => $ad,
            "form" => $form->createView()
        ]);
    }

    /**
     * Remove Ad.
     * 
     * @Route("/{slug}/delete", name="ad_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la supprimer !")
     *
     * @param Ad $ad
     * @return Response
     */
    public function delete(Ad $ad): Response
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                MessageConstant::ERROR_TYPE,
                "Vous ne pouvez pas supprimer cette annonce, car elle contient des réservations !"
            );
            return $this->redirectToRoute("ad_show", [
                'slug' => $ad->getSlug(),
                'id' => $ad->getId()
            ]);
        } else {
            $this->remove($ad);
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Annonce supprmée avec succès !"
            );
            return $this->redirectToRoute("ad_index");
        }
    }
}