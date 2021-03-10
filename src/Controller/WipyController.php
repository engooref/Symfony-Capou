<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EspTest;

class WipyController extends AbstractController
{
    #[Route('/esp', name: 'esp')]
    public function FonctionDeRecuperationDesDonneesDuGetVal()
    {
        $esp = $this->getDoctrine()->getRepository(EspTest::class)->findAll();
        return $this->render('wipy/index.html.twig', [
            'esp' => $esp,
        ]);
    }
    
    #[Route('/input', name: 'input')]
    public function input()
    {
        $val = $_GET['val'];
        $esp = new EspTest();
        $esp->setval($val);
        if($esp->getVal()){
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($esp);
            $doctrine->flush();
            return $this->redirectToRoute('esp');
        }
    }
}
