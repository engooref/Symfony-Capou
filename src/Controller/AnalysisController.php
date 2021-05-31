<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalysisController extends AbstractController
{
    #[Route('/analyses', name: 'analysis')]
    public function index(): Response
    {
        return $this->render('analysis/index.html.twig', [
            'controller_name' => 'AnalysisController',
        ]);
    }
}
