<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AbonneType extends AbstractType
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
            ->add('roles', ChoiceType::class,[
                "choices"=>[
                    "Administrateur"=>"ROLE_AMIN",
                    "Biliothècaire"=>"ROLE_BIBLIOTHECAIRE",
                    "Abonne"=>"ROLE_ABONNE"
                ],
                "multiple"=>true,
                "expanded"=>true,
                
            ])
            ->add('password', TextType::class,[
                /*quand  l'option 'mapped' vaut false, cela signifie que l'input *'password ne doit pas être considéré ^c 1 propriete de l'obj Abonne => si on rempli l'input, la valeur ne sera pas affectée directement à l'obj Abonne
                */
                "mapped"=>false,
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
                    ]),
                    /*
                    new Regex([
                        "pattern"=>"/^(?=.{6,10}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/",
                        "message" => "Le mot de passe doit comporter entre 6 et 10 caractères, une minuscule, une majuscule, un chiffre, un caractère spécial"
                    ])
                    */
                ]
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
