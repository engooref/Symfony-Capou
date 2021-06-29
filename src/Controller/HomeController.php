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
use App\Entity\Parcelle;
use App\Entity\Operateur;

use DateTime;
use DateInterval;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        //Piquet
        $nbpiquet_print = 10;
        $nb_piquet = count($this->getDoctrine()->getManager()->getRepository(Piquet::class)->findByEtat(1));
        if ($nb_piquet < $nbpiquet_print) $nbpiquet_print = $nb_piquet;
        for($k = 0; $k<$nbpiquet_print; $k++){
            $donnees_piquet[$k] = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findOneByIdPiquet([$k+1],['horodatage' => 'desc']);
            $parcellePiquet[$k] = $this->getDoctrine()->getManager()->getRepository(Piquet::class)->findOneById($k+1)->getIdParcelle();
        }
        
        //Armoire
        $etat_armoire = $this->getDoctrine()->getManager()->getRepository(Armoire::class)->findAll();
        $etat_armoire = $etat_armoire[0]->getEtat();
        $donnees_armoire = $this->getDoctrine()->getManager()->getRepository(DonneesArmoire::class)->findOneBy([],[]);

        //Electrovanne
        $nbvanne_print = 5;
        $nb_vanne = count($this->getDoctrine()->getManager()->getRepository(ElectroVanne::class)->findByEtat(1));
        if ($nb_vanne < $nbvanne_print) $nbvanne_print = $nb_vanne;
        for($k = 0; $k<$nbvanne_print; $k++){
            $donnees_vanne[$k] = $this->getDoctrine()->getManager()->getRepository(DonneesVanne::class)->findOneByIdVanne([$k+1],['horodatage' => 'desc']);
            $parcelleVanne[$k] = $this->getDoctrine()->getManager()->getRepository(ElectroVanne::class)->findOneById($k+1)->getIdParcelle();
            
        }
        //Parcelle
        $nb_parcelle = count($this->getDoctrine()->getManager()->getRepository(Parcelle::class)->findAll());
        for($k = 0; $k<$nb_parcelle; $k++){
           $parcelle[$k] = $this->getDoctrine()->getManager()->getRepository(Parcelle::class)->findOneById($k+1)->getLabel();
           $pPiquet[$k] = count($this->getDoctrine()->getManager()->getRepository(Piquet::class)->findByIdParcelle($k+1));
           $pVanne[$k] = count($this->getDoctrine()->getManager()->getRepository(ElectroVanne::class)->findByIdParcelle($k+1));         
           $pOperateur[$k] = count($this->getDoctrine()->getManager()->getRepository(Operateur::class)->findByIdParcelle($k+1));
        }
        
        return $this->render('home/index.html.twig', [
            'nb_piquet' => $nb_piquet,
            'nbpiquet_print' => $nbpiquet_print,
            'parcellePiquet' => $parcellePiquet,
            'donnees_piquet' => $donnees_piquet,
            'nb_vanne' => $nb_vanne,
            'nbvanne_print' => $nbvanne_print,
            'parcelleVanne' => $parcelleVanne,
            'donnees_vanne' => $donnees_vanne,
            'etat_armoire' => $etat_armoire,
            'donnees_armoire' => $donnees_armoire,
            'nb_parcelle' => $nb_parcelle,
            'donnees_parcelle' => $parcelle,
            'nbParcelle_piquet' => $pPiquet,
            'nbParcelle_vanne' => $pVanne,
            'nbParcelle_operateur' => $pOperateur,
        ]);
        
    }
}
