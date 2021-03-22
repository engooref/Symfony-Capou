<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PilotageController extends AbstractController
{
    #[Route('/pilotage', name: 'pilotage')]
    public function index(): Response
    {
        return $this->render('pilotage/index.html.twig', [
        ]);
    }
}
