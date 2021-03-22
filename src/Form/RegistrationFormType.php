<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class,[
                "constraints" => [
                    new Length([
                        "max" => 180,
                        "maxMessage" => "le titre doit comporter 180 caracteres max",
                        "min" => 2,
                        "minMessage" => "le titre doit comporter au moins 2 caracteres"
                    ]),
                    new NotBlank([
                        "message" => "le pseudo ne peut pas etre vide"
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                "label" => "J'accepte les CGU",
                'constraints' => [
                    new IsTrue([
                        'message' => 'Accepter les C.G.U.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    
                    // new Length([
                        // 'min' => 6,
                      //  'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                     //   'max' => 4096,
                   // ]),

                   new Regex([ 
                    "pattern" => "/^(?=.{6,10}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/",
                    "message" => "Le mot de passe doit comporter entre 6 et 10 caractères, une minuscule, une majuscule, un chiffre, un caractère spécial"
                ])
                ],
            ])


            ->add('nom', TextType::class,[
                "label" => "Nom",
                "help" => "30 caracteres max",
                "required"=>false,
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
                "required"=>false,
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


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
