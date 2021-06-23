<?php

namespace App\Controller\Admin;

use App\Entity\Parcelle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Doctrine\ORM\EntityManagerInterface;


class ParcelleCrudController extends AbstractCrudController
{
    private $manager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public static function getEntityFqcn(): string
    {
        return Parcelle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('label'),
            AssociationField::new('idOperateurs', 'Operateur')->setHelp('Choisir les opérateurs appartenant à une parcelle'),
            AssociationField::new('idPiquets', 'Piquets')->setHelp('Choisir les piquets appartenant à une parcelle'),
            AssociationField::new('idElectrovannes', 'Electrovannes')->setHelp('Choisir les électrovannes appartenant à une parcelle'),
        ];
    }
    

    public function configureActions(Actions $actions): Actions
    {
        //$GroupAct = $this->manager->getRepository(Parcelle::class)->findOneById($entity->getId());
        //         $buttonDelete = Action::DELETE('sendInvoice', 'Send invoice', 'fa fa-envelope');
        
        
        $newDelete = Action::new('Supprimer')
        ->addCssClass('action-Supprimer text-danger')
        ->linkToRoute('deleteCrud', function (Parcelle $order) : Array {
            return [
                'entity' => $order->getId()
            ];
        });
        
        
            
            
            
            return $actions
            ->disable(Action::DELETE)
            ->add(Crud::PAGE_INDEX, $newDelete)
            ->reorder(Crud::PAGE_INDEX, [Action::EDIT])
            ;
    }
    
    
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Parcelle')
        ->setEntityLabelInPlural('Parcelles');
    }
}
