<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserController.
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     */
    public function index(User $user): Response
    {
        return $this->render('front_office/user/show.html.twig', [
            'user' => $user,
        ]);
    }
}