<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Class EditUserType.
 */
class EditUserType extends AbstractType
{
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
            ->add("roles", ChoiceType::class, [
                "label" => "Roles",
                "choices" => [
                    "Utilisateur" => "ROLE_USER",
                    "Administrateur" => "ROLE_ADMIN"
                ],
                "expanded" => true,
                "multiple" => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class
        ]);
    }
}