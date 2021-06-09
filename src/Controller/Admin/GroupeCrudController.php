<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
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


class GroupeCrudController extends AbstractCrudController
{
    private $manager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('label'),
            AssociationField::new('idOperateur', 'Operateur')->setHelp('Choisir les opérateurs appartenant à ce groupe'),
            AssociationField::new('idPiquets', 'Piquets')->setHelp('Choisir les piquets appartenant à ce groupe'),
        ];
    }
    
    public function configureActions(Actions $actions): Actions
    {        
        //$GroupAct = $this->manager->getRepository(Groupe::class)->findOneById($entity->getId());
//         $buttonDelete = Action::DELETE('sendInvoice', 'Send invoice', 'fa fa-envelope');
       
        
        $newDelete = Action::new('Supprimer')
        ->linkToRoute('deleteCrud', function (Groupe $order) : Array {
            return [
            'entity' => $order->getId()
            ];
        });
       
        return $actions
            ->disable(Action::DELETE)
            ->add(Crud::PAGE_INDEX, $newDelete)
        ;
    }
}
