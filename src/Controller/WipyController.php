<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EspTest;

class WipyController extends AbstractController
{
    #[Route('/esp', name: 'esp')]
    public function FonctionDeRecuperationDesDonneesDuGetVal()
    {
        return $this->render('wipy/index.html.twig');
    }
    
    #[Route('/input', name: 'input')]
    public function extract()
    {
        $longitude = $_GET['longitude'];
        $latitude = $_GET['latitude'];
        $esp = new EspTest();
        $esp->setlongitude($longitude);
        $esp->setlatitude($latitude);
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($esp);
        $doctrine->flush();
        return $this->redirectToRoute('esp');
    }
    
    #[Route('/extract', name: 'extract')]
    public function input()
    {
        $esp = $this->getDoctrine()->getRepository(EspTest::class)->findAll();
        return new JsonResponse($esp);
    }
    
}
