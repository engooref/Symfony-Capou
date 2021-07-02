<?php

namespace App\Controller\Admin;

use App\Entity\Parcelle;
use App\Entity\Operateur;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OperateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Operateur::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email')->setHelp('Choisir l\'adresse email'), 
            AssociationField::new('idParcelle', 'Parcelle')->setRequired(true)->setHelp('Choisir à quel parcelle l\'opérateur appartient'),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['createdAt' => 'DESC']);
    }
    
}
