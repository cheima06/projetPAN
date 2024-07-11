<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'exemple_class_sur_votre_champ',
                    'placeholder' => 'Votre prénom'
                ],
                // 'data' => 'abcdef',
                //'required'   => false,
                'empty_data' => 'John Doe',
                'row_attr' => ['class' => 'col-md-12', 'id' => '...'],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ],
                'row_attr' => ['class' => 'col-md-12', 'id' => '...'],

                ])
                ->add('Email', TextType::class, [
                    'label' => 'Email',
                    'attr' => [
                        'placeholder' => 'Votre email'
                    ],
                    'row_attr' => ['class' => 'col-md-12', 'id' => '...'],
    
                    ])
                ->add('PhoneNumber', TextType::class, [
                        'label' => 'Numero de telephone',
                        'attr' => [
                            'placeholder' => 'Votre numero de telephone'
                        ],
                        ])
                ->add('object', TextType::class, [
                'label' => 'Objet',
                'attr' => [
                    'placeholder' => 'Objet'
                ],
                'row_attr' => ['class' => 'col-md-12', 'id' => '...'],
        
                ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr'=>[
                    'placeholder'=>'Votre message'
                ]

                    // 'mapped' => false
                    // pour le mapped false -> si t'as un champ dans ton formulaire qui n'est pas lié à l'entité du formulaire
                    // et du coup tu dois dire a symfony, "ca c'est un champ extra n'essaie pas de le lier à l'entité
                    // depuis laquelle tu as créé ce formulaire
                    // ca peut etre pratique si par exemple tu veux demander a la personne d'accepter les conditions
                    // du site pour soumettre les données
                    // l'input check box sera pas forcément lié à un attribut de Contact
                
                ])
            //->add('date')
            ->add('save', SubmitType::class, [
                'label'=> 'Nous contacter',
                'attr' => ['class' => 'bouton',]
                ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
