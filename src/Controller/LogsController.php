<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogsController extends AbstractController
{
    #[Route('/logs', name: 'logs')]
    public function index(): Response
    {
        return $this->render('logs/index.html.twig', [
        ]);
    }
}
