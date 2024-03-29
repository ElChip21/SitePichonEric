<?php

namespace App\Controller\Admin;

use App\Entity\Rubrique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RubriqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rubrique::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Masquer l'ID dans le formulaire
            TextField::new('name', 'Nom'),
            TextEditorField::new('description', 'Description'),
            ImageField::new('image')
            ->setUploadDir('public/uploads/rubriques')
            ->setBasePath('uploads/rubriques')
            ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
            ->setRequired(false),        
        ];
    }
    
}
