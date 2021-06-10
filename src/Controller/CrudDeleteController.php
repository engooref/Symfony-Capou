<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Piquet;
use App\Entity\Operateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrudDeleteController extends AbstractController
{
    
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
        //echo $groupe->getId(); die();
        
        $operatorTab =    $manager->getRepository(Operateur::class)->findBy(['idGroupe'=>$groupe->getId()]);
        $piquetTab   =    $manager->getRepository(Piquet::class)->findBy(['idGroupe'=>$piquet->getId()]);
        
        //dump($operatorTab);die();
        
        if(!empty($operatorTab) || !empty($piquetTab)){
            $this->addFlash('danger', 'Impossible de supprimer le groupe ! La parcelle contient des operateurs ou des piquets, veuillez d\'abord les transferer dans un autre groupe');
        }
        else{
            $manager->remove($groupe);
            $manager->flush();
        }
        return $this->redirectToRoute('admin');
    }
}
