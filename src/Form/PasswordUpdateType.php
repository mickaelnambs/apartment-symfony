<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class PasswordUpdateType.
 */
class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("currentPassword", PasswordType::class, [
                "label" => "Mot de passe",
                "attr" => [
                    "autocomplete" => "off",
                    "placeholder" => "Votre mot de passe ..."
                ],
                "constraints" => [
                    new UserPassword()
                ]
            ])
            ->add("newPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options" => [
                    "label" => "Nouveau mot de passe",
                    "attr" => [
                        "placeholder" => "Le nouveau mot de passe ..."
                    ]
                    ],
                "second_options" => [
                    "label" => "Confirmation de mot de passe",
                    "attr" => [
                        "placeholder" => "Confirmation de nouveau mot de passe ..."
                    ]
                ],
                "constraints" => [
                    new NotBlank(),
                    new Length([
                        "min" => 8,
                        "minMessage" => "Votre mot de passe doit faire au moins 8 caractères !"
                    ])
                ]
            ])
        ;
    }
}