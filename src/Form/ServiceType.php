<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Rubrique;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('tarif')
            ->add('rubrique', EntityType::class, [
                'class' => Rubrique::class,
                'choice_label' => 'id',
            ])
            ->add('picture', FileType::class, [
                'label' => 'Picture',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/webp',

                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier png, jpeg, webp ou jpg',
                    ])
                ],
            ])
            ->add('devis', EntityType::class, [
                'class' => Devis::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
