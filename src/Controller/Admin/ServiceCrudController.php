<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image')
            ->setUploadDir('public/uploads/services')
            ->setBasePath('uploads/services')
            ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
            ->setRequired(false),
            IdField::new('id')->hideOnForm(),
            AssociationField::new('rubrique'),
            TextField::new('titre'),
            TextEditorField::new('description'),
            NumberField::new('tarif')->setLabel('Tarif (€)'),
            ChoiceField::new('type')
                ->setLabel('Type')
                ->setChoices([
                    'Cours particulier' => 'Cours particulier',
                    'Ecriture' => 'Ecriture',
                    'Traduction' => 'Traduction',
                    
                ]),
        ];
    }
}
