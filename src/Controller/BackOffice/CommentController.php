<?php

namespace App\Controller\BackOffice;

use App\Constant\MessageConstant;
use App\Entity\Comment;
use App\Constant\PageConstant;
use App\Controller\BaseController;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 * 
 * @Route("/admin/comments")
 */
class CommentController extends BaseController
{
    private CommentRepository $commentRepository;

    /**
     * CommentController constructor.
     *
     * @param EntityManagerInterface $em
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        EntityManagerInterface $em, 
        CommentRepository $commentRepository
    ) {
        parent::__construct($em);
        $this->commentRepository = $commentRepository;
    }

    /**
     * Permet d'afficher tous les commentaires.
     * 
     * @Route("/", name="admin_comment_index", methods={"GET","POST"})
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $this->commentRepository->findAll(),
            $request->query->getInt("page", PageConstant::DEFAULT_PAGE),
            PageConstant::NUM_ITEMS_PER_PAGE
        );
        return $this->render("back_office/comment/index.html.twig", [
            "comments" => $pagination
        ]);
    }

    /**
     * Permet de modifier un commentaire.
     * 
     * @Route("/{id}/edit", name="admin_comment_edit", methods={"GET","POST"})
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function edit(Comment $comment, Request $request): Response
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($comment)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Commentaire modifié avec succès !"
                );
                return $this->redirectToRoute("admin_comment_index");
            }
            $this->addFlash(
                MessageConstant::ERROR_TYPE,
                "Il y a une erreur lors de la modification !"
            );
            return $this->redirectToRoute("admin_comment_index", ["id" => $comment->getId()]);
        }
        return $this->render("back_office/comment/edit.html.twig", [
            "comment" => $comment,
            "form" => $form->createView() 
        ]);
    }

    /**
     * Permet de supprimer un commentaire.
     * 
     * @Route("/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @return Response
     */
    public function delete(Comment $comment): Response
    {
        if ($this->remove($comment)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Commentaire supprimé avec succès !"
            );
            return $this->redirectToRoute("admin_comment_index");
        }
    }
}
