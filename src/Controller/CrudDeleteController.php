<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Piquet;
use App\Entity\ElectroVanne;
use App\Entity\Operateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Admin\GroupeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use App\Controller\DashboardController;
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
        $manager = $this->getDoctrine()->getManager();
        $groupe = $manager->getRepository(Groupe::class)->findOneById(intval($request->query->get('routeParams')['entity']));
        $piquet = $manager->getRepository(Piquet::class)->findOneById(intval($request->query->get('routeParams')['entity']));
        $electrovanne = $manager->getRepository(ElectroVanne::class)->findOneById(intval($request->query->get('routeParams')['entity']));
        //dump($electrovanne); die();
        
        $operatorTab       =    $manager->getRepository(Operateur::class)->findBy(['idGroupe'=>$groupe->getId()]);
        $piquetTab         =    $manager->getRepository(Piquet::class)->findBy(['idGroupe'=>$piquet->getId()]);
        $electrovanneTab   =    $manager->getRepository(ElectroVanne::class)->findBy(['idGroupe'=>$electrovanne->getId()]);
        //dump($operatorTab);die();
        
        
        if(!empty($operatorTab)){
            $this->addFlash('danger', 'Impossible de supprimer le groupe ! La parcelle contient des operateurs, veuillez d\'abord les transferer dans un autre groupe');
        }
        else{
            
            
            foreach($piquetTab as $Piquet){ // Pour chaque op�rateurs contenus dans le groupe actuel, on les transfere dans le groupe par d�faut (1)
                $Piquet->setIdGroupe(null);
                $this->doctrineManager->flush();
            }
            
            foreach($electrovanneTab as $Electrovanne){ // Pour chaque op�rateurs contenus dans le groupe actuel, on les transfere dans le groupe par d�faut (1)
                $Electrovanne->setIdGroupe(null);
                $this->doctrineManager->flush();
            }
            
            $manager->remove($groupe);
            $manager->flush();
        }
        
        $url = $this->adminUrlGenerator
        ->setController(GroupeCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();
        
        return $this->redirect($url);
    }
}
