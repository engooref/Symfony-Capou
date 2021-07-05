<?php

namespace App\Controller;

use App\Entity\Piquet;
use App\Entity\Armoire;
use App\Entity\Parcelle;
use App\Entity\Operateur;
use App\Entity\ElectroVanne;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use App\Controller\DashboardController;
use App\Controller\Admin\ParcelleCrudController;

use Doctrine\ORM\EntityManagerInterface;


class CrudDeleteController extends AbstractController
{
    private $adminUrlGenerator;
    private $doctrineManager;
    
    public function __construct(AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $doctrineManager)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->doctrineManager = $doctrineManager;
    }
    
    
    public function getRefererRoute()
    {
        $request = $this->getRequest();
        
        //look for the referer route
        $referer = $request->headers->get('referer');
        $lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
        $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);
        
        $matcher = $this->get('router')->getMatcher();
        $parameters = $matcher->match($lastPath);
        $route = $parameters['_route'];
        
        return $route;
    }
    
    #[Route('/deleteCrud', name: 'deleteCrud')]
    public function deleteCrud(Request $request): Response
    {
        $parcelle = $this->doctrineManager->getRepository(Parcelle::class)->findOneById(intval($request->query->get('routeParams')['entity']));       
        $operatorTab        =    $this->doctrineManager->getRepository(Operateur::class)->findBy(['idParcelle'=>$parcelle->getId()]);
        $piquetTab          =    $this->doctrineManager->getRepository(Piquet::class)->findBy(['idParcelle'=>$parcelle->getId()]);
        $electrovanneTab    =    $this->doctrineManager->getRepository(ElectroVanne::class)->findBy(['idParcelle'=>$parcelle->getId()]);   
        $armoireTab         =    $this->doctrineManager->getRepository(Armoire::class)->findBy(['idParcelle'=>$parcelle->getId()]);
        
        if(!empty($operatorTab)){
            $this->addFlash('danger', 'Impossible de supprimer la Parcelle ! La parcelle contient des operateurs, veuillez d\'abord les transferer dans une autre Parcelle');
        }
        else{
            
            
            foreach($piquetTab as $Piquet){ // Pour chaque op�rateurs contenus dans la Parcelle actuel, on les transfere dans la Parcelle par d�faut (1)
                $Piquet->setIdParcelle(null);
                $this->doctrineManager->flush();
            }
            
            foreach($electrovanneTab as $Electrovanne){ // Pour chaque op�rateurs contenus dans la Parcelle actuel, on les transfere dans la Parcelle par d�faut (1)
                $Electrovanne->setIdParcelle(null);
                $this->doctrineManager->flush();
            }
            
            foreach($armoireTab as $Armoire){ // Pour chaque armoires contenus dans la Parcelle actuel, on les transfere dans la Parcelle par d�faut (1)
                $Armoire->setIdParcelle(null);
                $this->doctrineManager->flush();
            }
            
            $this->doctrineManager->remove($parcelle);
            $this->doctrineManager->flush();
        }
        
        $url = $this->adminUrlGenerator
        ->setController(ParcelleCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();
        
        return $this->redirect($url);
    }
}
