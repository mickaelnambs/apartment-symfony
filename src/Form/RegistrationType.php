<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Class RegistrationType.
 */
class RegistrationType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email", EmailType::class, [
                "label" => "Adresse email",
                "attr" => [
                    "placeholder" => "Entrez votre adresse email..."
                ]
            ])
            ->add("firstName", TextType::class, [
                "label" => "Prenom(s)",
                "attr" => [
                    "placeholder" => "Entrez votre prenom..."
                ]
            ])
            ->add("lastName", TextType::class, [
                "label" => "Nom de famille",
                "attr" => [
                    "placeholder" => "Entrez votre nom de famille..."
                ]
            ])
            ->add("password", PasswordType::class, [
                "label" => "Mot de passe",
                "attr" => [
                    "placeholder" => "Saisir votre mot de passe..."
                ]
            ])
            ->add("confirmPassword", PasswordType::class, [
                "label" => "Confirmation de mot passe",
                "attr" => [
                    "placeholder" => "Confirmer votre mot de passe..."
                ]
            ])
            ->add("introduction", TextType::class, [
                "label" => "Introduction",
                "attr" => [
                    "placeholder" => "Une petite introduction..."
                ]
            ])
            ->add("description", TextType::class, [
                "label" => "Description",
                "attr" => [
                    "placeholder" => "Une description detaillee..."
                ]
            ])
            ->add("phoneNumber", TextType::class, [
                "label" => "Telephone",
                "attr" => [
                    "placeholder" => "Entrez votre numero telephone..."
                ]
            ])
            ->add("avatar", FileType::class, [
                "label" => "Avatar",
                "required" => false,
                "attr" => [
                    "placeholder" => "Choisir votre avatar..."
                ],
                "data_class" => null,
                "constraints" => [
                    new Image()
                ]
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", User::class);
    }
}