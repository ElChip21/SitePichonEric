<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
                'mapped' => false,
            ])   

            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your email address',
                    ]),
                    new Regex([
                        'pattern' => '/^.+@.+\..+$/i',
                        'message' => 'L\'adresse email doit être au format valide.',
                    ]),
                ],
            ])
            
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                //'attr' => ['autocomplete' => 'new-password'],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrez un Mot de Passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre Mot de Passe doit faire au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de Passe',
                ],
                    'second_options' => [
                        'label' => 'Répétez le mot de passe',

                    ],
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                    // Instead of being set onto the object directly,
                    // this is read and encoded in the controller

                ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
