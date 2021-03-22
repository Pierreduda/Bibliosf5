<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RechType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                "label"=>"Recherche",
                "constraints" => [
                    new NotBlank([
                        "message" => "le champ ne doit pas etre vide"
                    ]),
                    new Length([
                        "min" => 2,
                        "minMessage" => "la recherche doit comporter au moins 2 caracteres"

                    ])
                ]
            ])
            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "btn-success"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
