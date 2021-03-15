<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Piquet;

class PiquetController extends AbstractController
{
    /**
     * @Route("/piquet/{id}", name="piquet")
     */
    public function index(int $id): Response
    {
        $piquet = $this->getDoctrine()
        ->getRepository(Piquet::class)
        ->find($id);
        if (!$piquet) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
                );
        }
            
        return $this->render('piquet/index.html.twig', [
            'piquet' => $piquet,
        ]);
    }
}
