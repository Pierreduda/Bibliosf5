<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class Abonne2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class,[
                "label" => "Pseudo",
                "help" => "180 caracteres max",
                "constraints" => [
                    new Length([
                        "max" => 180,
                        "maxMessage" => "le titre doit comporter 180 caracteres max",
                        "min" => 2,
                        "minMessage" => "le titre doit comporter au moins 2 caracteres"
                    ]),
                    new notBlank([
                        "message" => "le titre ne peut pas etre vide"
                    ])
                ]
            ])
            /*->add('role', TextType::class)*/
            ->add('password', PasswordType::class,[
                "label" => "mdp"])
            ->add('nom', TextType::class,[
                "label" => "Nom",
                "help" => "180 caracteres max",
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "le titre doit comporter 30 caracteres max",
                        "min" => 2,
                        "minMessage" => "le titre doit comporter au moins 2 caracteres"
                    ]),
                    new notBlank([
                        "message" => "le titre ne peut pas etre vide"
                    ])
                ]
            ])
            ->add('prenom', TextType::class,[
                "label" => "Prenom",
                "help" => "30 caracteres max",
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "le titre doit comporter 30 caracteres max",
                        "min" => 2,
                        "minMessage" => "le titre doit comporter au moins 2 caracteres"
                    ]),
                    new notBlank([
                        "message" => "le titre ne peut pas etre vide"
                    ])
                ]
            ])
            ->add('submit', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
