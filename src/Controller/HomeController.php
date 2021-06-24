<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DonneesArmoire;
use App\Entity\DonneesVanne;
use App\Entity\DonneesPiquet;
use App\Entity\Armoire;
use App\Entity\ElectroVanne;
use App\Entity\Piquet;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        //Piquet
        $piquet = $this->getDoctrine()->getManager()->getRepository(Piquet::class)->findByEtat(1);
        $nb_piquet = count($piquet);
        $donnees_piquet = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findOneBy([],['horodatage' => 'desc']);
        $temperature_min = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findOneBy([],['temperature' => 'asc']);
        $temperature_max = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findOneBy([],['temperature' => 'desc']);
        
        //Armoire
        $etat_armoire = $this->getDoctrine()->getManager()->getRepository(Armoire::class)->findAll();
        $etat_armoire = $etat_armoire[0]->getEtat();
        $donnees_armoire = $this->getDoctrine()->getManager()->getRepository(DonneesArmoire::class)->findOneBy([],[]);

        //Electrovanne
        $vanne = $this->getDoctrine()->getManager()->getRepository(ElectroVanne::class)->findByEtat(1);
        $nb_vanne = count($vanne);
        $donnees_vanne = $this->getDoctrine()->getManager()->getRepository(DonneesVanne::class)->findOneBy([],['horodatage' => 'desc']);

        return $this->render('home/index.html.twig', [
            'nb_piquet' => $nb_piquet,
            'temperature_minimale' => $temperature_min,
            'temperature_maximale' => $temperature_max,
            'donnees_piquet' => $donnees_piquet,
            'nb_vanne' => $nb_vanne,
            'donnees_vanne' => $donnees_vanne,
            'etat_armoire' => $etat_armoire,
            'donnees_armoire' => $donnees_armoire,
        ]);
        
    }
}
