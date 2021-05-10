<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\ProfileUpdateType;
use App\Form\PasswordUpdateType;
use App\Constant\MessageConstant;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AccountController extends BaseController
{
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * AccountController constructor.
     * 
     * @param EntityManagerInterface $em
     * @param PasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $em, 
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct($em);
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Permet de créer un compte utilisateur.
     * 
     * @Route("/register", name="app_register", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response 
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $avatarFile = $form->get("avatar")->getData();
            if (!empty($avatarFile)) $this->uploadAvatar($avatarFile, $user);
            if ($this->save($user)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Account created successfully"
                );
                return $this->redirectToRoute("app_login");
            }
        }

        return $this->render("front_office/account/registration.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * Permet à l'utilisateur de modifier son profil.
     * 
     * @Route("/profile/update", name="profile_update", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     */
    public function profileUpdate(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get("avatar")->getData();
            if (!empty($avatarFile)) $this->uploadAvatar($avatarFile, $user);

            if ($this->save($user)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Votre profil a bien été mis à jour avec succès !"
                );
                return $this->redirectToRoute("app_home");
            }
            return $this->redirectToRoute("profile_update");
        }
        return $this->render("front_office/account/profile_update.html.twig", [
            "form" => $form->createView(),
            "user" => $user
        ]);
    }

    /**
     * Permet à l'utilisateur de modifier son mot de passe.
     * 
     * @Route("/update/password", name="password_update", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     */
    public function updatePassword(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->passwordEncoder->encodePassword($user, $form->get("newPassword")->getData());
            $user->setPassword($hash);

            if ($this->save($user)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Mot de passe modifié avec succès !"
                );
                return $this->redirectToRoute("app_logout");
            }
            return $this->redirectToRoute("update_password");
        }
        return $this->render("front_office/account/password_update.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil d'un user authentifié.
     * 
     * @Route("/account", name="my_account")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount(): Response
    {
        return $this->render("front_office/user/show.html.twig", [
            "user" => $this->getUser()
        ]);
    }

    /**
     * Permet d'afficher la liste des réservations faites par l'utilisateur.
     * 
     * @Route("/accounts/bookings", name="account_bookings")
     *
     * @return Response
     */
    public function bookings(): Response
    {
        return $this->render("front_office/account/bookings.html.twig");
    }

    /**
     * Login.
     * 
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response 
    {
        return $this->render("front_office/account/login.html.twig", [
            "username" => $authenticationUtils->getLastUsername(),
            "hasError" => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * Logout.
     * 
     * @Route("/logout", name="app_logout")
     *
     * @return void
     */
    public function logout()
    {
        // Empty.
    }

    /**
     * @param File $file
     * @param User $user
     * @return User
     */
    public function uploadAvatar(File $file, User $user): User 
    {
        $filename = bin2hex(random_bytes(6)) . "." . $file->guessExtension();
        $file->move($this->getParameter("uploadAvatarDir"), $filename);
        $user->setAvatar($filename);

        return $user;
    }
}
