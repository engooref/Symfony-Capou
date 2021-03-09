<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WipyController extends AbstractController
{
    #[Route('/wipy', name: 'wipy')]
    public function index(): Response
    {
        return $this->render('wipy/index.html.twig', [
            'controller_name' => 'WipyController',
        ]);
    }
}
