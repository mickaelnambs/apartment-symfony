<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType.
 */
class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("rating", IntegerType::class, [
                "label" => "Note sur 5",
                "attr" => [
                    "placeholder" => "Veuillez indiquer votre note de 1 à 5",
                    "min" => 0,
                    "max" => 5
                ]
            ])
            ->add("content", TextareaType::class, [
                "label" => "Votre avis / témoignage",
                "attr" => [
                    "placeholder" => "N'hésitez pas à être très précis, cela aidera nos futurs voyageurs !"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
