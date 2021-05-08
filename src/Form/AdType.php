<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class AdType.
 */
class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, [
                "label" => "Titre",
                "attr" => [
                    "placeholder" => "Taper un super titre pour votre annonce..."
                ]
            ])
            ->add("price", MoneyType::class, [
                "label" => "Prix",
                "attr" => [
                    "placeholder" => "Indiquez le prix que vous voulez pour une nuit..."
                ]
            ])
            ->add("introduction", TextType::class, [
                "label" => "Introduction",
                "attr" => [
                    "placeholder" => "Donnez une description globale de l'annonce..."
                ]
            ])
            ->add("content", TextareaType::class, [
                "label" => "Description détaillée",
                "attr" => [
                    "placeholder" => "Tapez une description qui donne vraiment envie de venir chez vous..."
                ]
            ])
            ->add("rooms", IntegerType::class, [
                "label" => "Nombre de chambres",
                "attr" => [
                    "placeholder" => "Le nombre de chambres disponibles...",
                    "min" => 1,
                    "max" => 5
                ]
            ])
            ->add("medias", CollectionType::class, [
                "entry_type" => MediaType::class,
                "label" => false,
                "entry_options" => [
                    "label" => false,
                ],
                "allow_add" => true,
                "allow_delete" => true,
                "by_reference" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}