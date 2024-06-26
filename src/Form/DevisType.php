<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName')
            ->add('LastName')
            ->add('email')
         
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'titre',
                'expanded' => true, // Afficher les services sous forme de cases à cocher
                'multiple' => true, // Permettre la sélection de plusieurs services
                'mapped' => true, // Associer les services sélectionnés au devis
            ])

            
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 6], // Définir le nombre de lignes du champ de texte
            ]);    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
