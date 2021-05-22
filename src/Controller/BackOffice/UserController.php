<?php

namespace App\Controller\BackOffice;

use App\Constant\MessageConstant;
use App\Constant\PageConstant;
use App\Controller\BaseController;
use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 * 
 * @Route("/admin/users")
 */
class UserController extends BaseController
{
    private UserRepository $userRepository;

    /**
     * UserController constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        parent::__construct($em);
        $this->userRepository = $userRepository;
    }

    /**
     * Permet d'afficher tous les users.
     * 
     * @Route("/", name="admin_user_index", methods={"GET","POST"})
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $this->userRepository->findAll(),
            $request->query->getInt("page", PageConstant::DEFAULT_PAGE),
            PageConstant::NUM_ITEMS_PER_PAGE
        );
        return $this->render("back_office/user/index.html.twig", [
            "users" => $pagination
        ]);
    }

    /**
     * Permet de modifer le profil d'un user.
     * 
     * @Route("/{id}/update/profile", name="admin_profile_update", methods={"GET","POST"})
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function updateProfile(User $user, Request $request): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($user)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Profil modifié avec succès !"
                );
                return $this->redirectToRoute("admin_user_index");
            }
            $this->addFlash(
                MessageConstant::ERROR_TYPE,
                "Il y a une erreur lors de la modification !"
            );
            return $this->redirectToRoute("admin_user_edit", ["id" => $user->getId()]);
        }
        return $this->render("back_office/user/profile_update.html.twig", [
            "form" => $form->createView(),
            "user" => $user
        ]);
    }
}
