<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                "label" => "Titre du Livre",
                "help" => "50 caracteres max",
                "constraints" => [
                    new Length([
                        "max" => 50,
                        "maxMessage" => "le titre doit comporter 50 caracteres max",
                        "min" => 2,
                        "minMessage" => "le titre doit comporter au moins 2 caracteres"
                    ]),
                    new notBlank([
                        "message" => "le titre ne peut pas etre vide"
                    ])
                ]
            ])
            ->add('auteur', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 50,
                        "maxMessage" => "le titre doit comporter 50 caracteres max",
                        "min" => 2,
                        "minMessage" => "le titre doit comporter au moins 2 caracteres"

                    ])
                ]
            ])

            ->add('couverture', FileType::class, [
                "mapped"=>false,
                "required"=>false,
                "constraints"=>[
                    new File([
                        "mimeTypes"=>["image/gif", "image/jpeg", "image/png"],
                        "mimeTypesMessage"=>"Les formats autorisés sont gif, jpg, png",
                        "maxSize"=>"2048k",
                        "maxSizeMessage"=>"le fichier ne doit pas faire plus de 2mo"
                    ])
                ]
            ])
            /*
                        ->add('civilite', ChoiceType::class,[
                            "choices"=>[
                                "Mr"=>true,
                                "Mme"=>false,
                            ]
                        ])
            */
            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "btn-success"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
