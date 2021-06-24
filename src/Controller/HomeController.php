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
        $piquet = $this->getDoctrine()->getManager()->getRepository(Piquet::class)->findByEtat(1);
        $nb_piquet = count($piquet);
        $donnees_piquet = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findOneBy([],['horodatage' => 'desc']);
        $temperature_min = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findOneBy([],['temperature' => 'asc']);
        $temperature_max = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findOneBy([],['temperature' => 'desc']);
        
//         $date = new dateTime();
//         $date6h = DateTime::createFromFormat('H:i:s', '06:00:00');
        
//         $date = $date6h;
//         $dateadd = $date->add(new DateInterval('PT2H'));
// //         $datePeriode = $date->sub(new DateInterval('PT6H'));
//         $dataPiq = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class)->findByDateBetween($date6h,$dateadd);
//         dump($dataPiq); die();
        
        //Armoire
        $etat_armoire = $this->getDoctrine()->getManager()->getRepository(Armoire::class)->findAll();
        $etat_armoire = $etat_armoire[0]->getEtat();
        $donnees_armoire = $this->getDoctrine()->getManager()->getRepository(DonneesArmoire::class)->findOneBy([],[]);

        //Electrovanne
        $vanne = $this->getDoctrine()->getManager()->getRepository(ElectroVanne::class)->findByEtat(1);
        $nb_vanne = count($vanne);
        $donnees_vanne = $this->getDoctrine()->getManager()->getRepository(DonneesVanne::class)->findOneBy([],['horodatage' => 'desc']);

        //Parcelle
        $parcelle = $this->getDoctrine()->getManager()->getRepository(Parcelle::class)->findAll();
        $nb_parcelle = count($parcelle);

        
        for($k = 0; $k<$nb_parcelle; $k++){
           $parcelle[$k] = $this->getDoctrine()->getManager()->getRepository(Parcelle::class)->findOneById($k+1)->getLabel();
           $pPiquet[$k] = count($this->getDoctrine()->getManager()->getRepository(Piquet::class)->findByIdParcelle($k+1));
           $pVanne[$k] = count($this->getDoctrine()->getManager()->getRepository(ElectroVanne::class)->findByIdParcelle($k+1));         
           $pOperateur[$k] = count($this->getDoctrine()->getManager()->getRepository(Operateur::class)->findByIdParcelle($k+1));

        }
//         dump($pPiquet);
//         dump($pVanne);
//         dump($pOperateur);
//         die();
        
        return $this->render('home/index.html.twig', [
            'nb_piquet' => $nb_piquet,
            'temperature_minimale' => $temperature_min,
            'temperature_maximale' => $temperature_max,
            'donnees_piquet' => $donnees_piquet,
            'nb_vanne' => $nb_vanne,
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
