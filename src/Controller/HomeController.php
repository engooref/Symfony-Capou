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
        $piquetParse = 0;
        $piquetDb = $this->getDoctrine()->getManager()->getRepository(Piquet::class)->findByEtat(1);
        for($piquetParse = 0; ($piquetParse < $nbpiquet_print) && ($piquetParse < count($piquetDb)); $piquetParse++){
            $donnees_piquet[$piquetParse] = $this->_sortArray($piquetDb[$piquetParse]->getIdDonnees()->getValues())[0];
            $parcellePiquet[$piquetParse] = $piquetDb[$piquetParse]->getIdParcelle();
        }
      
        //Armoire
        $armoireDb = $this->getDoctrine()->getManager()->getRepository(Armoire::class)->findAll()[0];
        $etat_armoire = $armoireDb->getEtat();
        $donnees_armoire = $armoireDb->getIdDonnees()->getValues()[0];

        //Electrovanne
        $nbvanne_print = 5;
        $vanneParse = 0;
        $vanneDb = $this->getDoctrine()->getManager()->getRepository(ElectroVanne::class)->findAll();
        for($vanneParse = 0; ($vanneParse < $nbvanne_print) && ($vanneParse < count($vanneDb)); $vanneParse++){
            $donnees_vanne[$vanneParse] = $this->_sortArray($vanneDb[$vanneParse]->getIdDonnees()->getValues())[0];
            $parcelleVanne[$vanneParse] = $vanneDb[$vanneParse]->getIdParcelle();
        }

        //Parcelle
        $parcelleDb = $this->getDoctrine()->getManager()->getRepository(Parcelle::class)->findAll();
        $parcelleParse = 0;
        foreach($parcelleDb as $parcelle) {
            $parcelleParse = array_search($parcelle, $parcelleDb);
            $labelParcelle[$parcelleParse] = $parcelle->getLabel();
            $pPiquet[$parcelleParse] = count($parcelle->getIdPiquets()->getValues());
            $pVanne[$parcelleParse] = count($parcelle->getIdElectrovannes()->getValues());
            $pOperateur[$parcelleParse] = count($parcelle->getIdOperateurs()->getValues());
        
        }
      
        return $this->render('home/index.html.twig', [
            'nb_piquet' => $piquetParse,
            'donnees_piquet' => $donnees_piquet,

            'etat_armoire' => $etat_armoire,
            'donnees_armoire' => $donnees_armoire,

            'nb_vanne' => $vanneParse,
            'donnees_vanne' => $donnees_vanne,

            'nb_parcelle' => $parcelleParse,
            'donnees_parcelle' => $labelParcelle,
            'nbParcelle_piquet' => $pPiquet,
            'nbParcelle_vanne' => $pVanne,
            'nbParcelle_operateur' => $pOperateur,
        ]);
        
    }

    private function _sortArray(Array $fields) : Array 
    {
        $sortArray = array();
        for($index = 0; $index < count($fields); $index++){    
            $max = $fields[$index];
            for($parcelleParse = $index; ($parcelleParse < count($fields)); $parcelleParse++){
                if($max->getHorodatage() < $fields[$parcelleParse]->getHorodatage()){
                    $max = $fields[$parcelleParse];
                }
            }
            $fields[array_search($max, $fields)] = $fields[$index];
            $fields[$index] = $max;
        }
        
        $sortArray = $fields;
        return $sortArray;
    }
}
