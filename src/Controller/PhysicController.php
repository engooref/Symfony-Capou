<?php

namespace App\Controller;

use App\Entity\Parcelle;
use App\Entity\Piquet;
use App\Entity\Armoire;
use App\Entity\ElectroVanne;

use App\Entity\DonneesArmoire;
use App\Entity\DonneesPiquet;
use App\Entity\DonneesVanne;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use DateTime;
use DateInterval;

class PhysicController extends AbstractController
{    
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    #[Route('/controle', name: 'control')]
    public function control() : Response {
        return $this->render("control/index.html.twig");
    }
    
    #[Route('/getData', name: 'getData')]
    public function getData() : Response {
        if(!isset($_GET['type'], $_GET['id'])) return new Response(Response::HTTP_NOT_FOUND);
        $type = $_GET['type'];
        $id = $_GET['id'];
        $obj = null;
        //  1 -> Electrovanne | 2 -> Piquet | 3 -> Armoire
        switch (intval($type))
        {
            case 1:
                $obj = $this->manager->getRepository(ElectroVanne::class);
                break;
            case 2:
                $obj = $this->manager->getRepository(Piquet::class);
                break;
            case 3:
                $obj = $this->manager->getRepository(Armoire::class);
                break;
        }
        $obj = $obj->findOneById($id);
        if(!$obj) {
            return new Response(Response::HTTP_NOT_FOUND);
        }
        $dataObj = $obj->getIdDonnees()->getValues();
        
        if((intval($type) == 1) || (intval($type) == 2)) {
            $dateMin = (DateTime::createFromInterface(end($dataObj)->getHorodatage()))->sub(new DateInterval("P1W"));
            $dateMax = DateTime::createFromInterface(end($dataObj)->getHorodatage());
            
            $dataCount = count($dataObj);
            
            for($i = 0; $i < $dataCount; $i++){
                $dateObj = $dataObj[$i]->getHorodatage();
                if(!(($dateObj->diff($dateMin)->invert) && !($dateObj->diff($dateMax)->invert)))
                    unset($dataObj[$i]);
            }
        }     
        
        return new JsonResponse(array("Object" => $obj, "Data" => $dataObj));
    }

    #[Route('/getDataPiquet', name: 'getDataPiquet')]
    public function getDataPiquet() : Response {
        $periode = "1sem";

        if(isset($_POST['periode'])){
            $periode = $_POST['periode'];
        }

        $date = new dateTime();

        if(($periode == "24h") || ($periode == "")){
            $datePeriode = $date->sub(new DateInterval('P1D'));     // Période de 24h
        } else if ($periode == "1sem"){
            $datePeriode = $date->sub(new DateInterval('P7D'));     // Période de 1 semaine
        }   else if ($periode == "1mois"){
            $datePeriode = $date->sub(new DateInterval('P1M'));     // Période de 1 mois
        }  else if ($periode == "1an"){
            $datePeriode = $date->sub(new DateInterval('P1Y'));     // Période de 1 an
        }  else if ($periode == "10ans"){
            $datePeriode = $date->sub(new DateInterval('P10Y'));    // Période de 10 ans
        }  
        
        $dataPiquet = $this->manager->getRepository(DonneesPiquet::class);
        $obj = $dataPiquet->findByDate($datePeriode);

        foreach($obj as $piquet)
        {
            $id[] = $piquet->getId();
            $Horodatage = $piquet->getHorodatage();
            $horodatage[] = $Horodatage->format('d/m/Y H:i:s');
            $temp[] = $piquet->getTemperature();
            $humi[] = $piquet->getHumidite();
        }

        return new JsonResponse(array("Id" => $id, "Heure" => $horodatage, "Temp"=> $temp, "Humi" => $humi));
    }
    
    #[Route('/mapsControl', name: 'mapsControl')]
    public function mapsControl()
    {
        $electrovanne = array();
        $piquet = array();
        
        $adminRoles = $this->getUser()->getRoles();
        if($adminRoles[0] === "ROLE_ADMIN") {
            $electrovannes = $this->manager->getRepository(Electrovanne::class)->findAll();
            $piquets = $this->manager->getRepository(Piquet::class)->findAll();
            for($i = 0; $i < count($electrovannes); $i++) {
                if($electrovannes[$i]->getEtat()){
                    $val = $electrovannes[$i]->getIdDonnees()->GetValues();
                    $data = end($val);
                    $coordsElec["id"] = $electrovannes[$i]->getId();
                    $coordsElec["gps"] = array("latitude" => $data->getLatitude(), "longitude" => $data->getLongitude());
                    array_push($electrovanne, $coordsElec);
                }
            }
            for($i = 0; $i < count($piquets); $i++) {
                if($piquets[$i]->getEtat()){
                    $val = $piquets[$i]->getIdDonnees()->GetValues();
                    $data = end($val);
                    $coordsPiq["id"] = $piquets[$i]->getId();
                    $coordsPiq["gps"] = array("latitude" => $data->getLatitude(), "longitude" => $data->getLongitude());
                    array_push($piquet, $coordsPiq);
                }
            }  
        } else {
            $parcelle = $this->getUser()->getIdParcelle();
            $electrovanneDb = $parcelle->getIdElectrovannes();
            $piquetDb = $parcelle->getIdPiquets();
            for($i = 0; $i < count($electrovanneDb); $i++){
                if($electrovanneDb[$i]->getEtat()){
                    $val = $electrovanneDb[$i]->getIdDonnees()->GetValues();
                    $data = end($val);
                    
                    $coordsElec["id"] = $electrovanneDb[$i]->getId();
                    $coordsElec["gps"] = array("latitude" => $data->getLatitude(), "longitude" => $data->getLongitude());
                    array_push($electrovanne, $coordsElec);
                }
            }
            for($i = 0; $i < count($piquetDb); $i++){
                if($piquetDb[$i]->getEtat()){
                    $val = $piquetDb[$i]->getIdDonnees()->GetValues();
                    $data = end($val);
                    
                    $coordsPiq["id"] = $piquetDb[$i]->getId();
                    $coordsPiq["gps"] = array("latitude" => $data->getLatitude(), "longitude" => $data->getLongitude());
                    array_push($piquet, $coordsPiq);
                }
            }
        }  
        return new JsonResponse(array("1" => $electrovanne, "2" => $piquet));
       
    }
    
    #[Route('/input', name: 'input')]
    public function input() : Response
    {
        // ==================== TRAME ==================
        // M1=type;id;idMaitre;...&M2=type;id;idMaitre;...

        // On traite le nombre de module envoyé à partir du GET
        $array_nb_module = array();
        for($i = 1; isset($_GET['M'.strval($i)]); $i++){
            array_push($array_nb_module, $_GET['M'.strval($i)]);
        }
        if(!isset($_GET['M1'])) return new Response(Response::HTTP_NOT_FOUND);
        
        // On traite les données du module envoyées
        $newData = null;
        foreach($array_nb_module as $module) {
            $array_data_module = explode(';', $module);
            $type = $array_data_module[0];  // Length : 0 -> type
            // 1 -> Electrovanne  | 2 -> Piquet | 3 -> Armoire
            switch (intval($type))
            {
                case 1:
                    $newData = $this->InputElectrovanne($array_data_module);
                    break;
                case 2:
                    $newData = $this->InputPiquet($array_data_module);
                    break;
                case 3:
                    $newData = $this->InputArmoire($array_data_module);
                    break;
                default:
                    return new Response(Response::HTTP_NOT_FOUND);
            }
            if($newData === -1) {
                return new Response(Response::HTTP_NOT_ACCEPTABLE);
            }
            if($newData) {
                $this->manager->persist($newData);
                $this->manager->flush();
            }
        }
        return new Response(Response::HTTP_OK);
    }
    
    private function createEsc($type=null, $id=null, $idMaitre=null, $ipModule=null, $etatMaitre=null) {
        
        if(!isset($type) && !isset($id) && !isset($idCen) && !isset($ipCen)) {
            return -1;
        }

        $newObj = null;
        
        // 1 -> Electrovanne | 2 -> Piquet | 3 -> Armoire
        switch (intval($type))
        {  
            case 1:
                $newObj = new ElectroVanne;
                $newObj->setId($id);
                $newObj->setEtat(True);
                $newObj->setIp($ipModule);
                break;
            case 2:
                $newObj = new Piquet;
                $newObj->setId($id);
                $newObj->setEtat(True);
                $newObj->setIdMaitreRadio($this->manager->getRepository(ElectroVanne::class)->findOneById($idMaitre));
                break;    
            case 3:
                $newObj = new Armoire;
                $newObj->setId($id);
                $newObj->setEtat($etatMaitre);
                $newObj->setIp($ipModule);
                break;                 
        }

        $this->manager->persist($newObj);
        $this->manager->flush();
        
        return 0;
    }

    private function InputElectrovanne($inputTrameElectrovanne) {
        // ==================== TRAME ELECTROVANNE ==================
        // [type;id;ip;horodatage;longitude;latitude;batterie]    
        
        // Trame de 7 données pour l'ElectroVanne
        if(count($inputTrameElectrovanne) !== 7) return -1; // Trame pas complète renvoie -1
        
        $idElectroVanne = hexdec($inputTrameElectrovanne[1]);
        $ipElectroVanne = $inputTrameElectrovanne[2];
        $horodatage = $inputTrameElectrovanne[3];
        $longitude = $inputTrameElectrovanne[4];
        $latitude = $inputTrameElectrovanne[5];
        $batterie =  $inputTrameElectrovanne[6];
        
        $result = $this->manager->getRepository(DonneesVanne::class)->findByhorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        
        if($result){
            foreach($result as $val){
                if($val->getIdVanne()->getId() === $idElectroVanne){
                    return -1;
                }
            }
        }
        
        $electrovanne = $this->manager->getRepository(ElectroVanne::class)->findOneById($idElectroVanne);
        
        if(!isset($electrovanne)){
            $this->createEsc(1, $idElectroVanne, null, $ipElectroVanne);
        }
        
        $donneesVanne = new DonneesVanne;
        $donneesVanne->setIdVanne($this->manager->getRepository(ElectroVanne::class)->findOneById($idElectroVanne));
        $donneesVanne->setHorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        $donneesVanne->setLatitude($latitude);
        $donneesVanne->setLongitude($longitude);
        $donneesVanne->setBatterie($batterie);
        return $donneesVanne;
    }
    
    private function InputPiquet($inputTramePiquet) {
        // ==================== TRAME PIQUET ==================
        // [type;id;idMaitre;temperature;horodatage;longitude;latitude;[humidite1:humidite2:humidite3:...];batterie]
        
        // Trame de 9 données pour le Piquet         
        if(count($inputTramePiquet) !== 9) return -1; // Trame pas complète renvoie -1
        
        $idPiquet = hexdec($inputTramePiquet[1]);
        $idMaitre = hexdec($inputTramePiquet[2]);
        $temperature = $inputTramePiquet[3];
        $horodatage = $inputTramePiquet[4];
        $longitude = $inputTramePiquet[5];
        $latitude = $inputTramePiquet[6];
        $humidite = explode(':', $inputTramePiquet[7]);
        $batterie =  $inputTramePiquet[8];
        
        $result = $this->manager->getRepository(DonneesPiquet::class)->findByhorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        
        if($result){
            foreach($result as $val){
                if($val->getIdPiquet()->getId() === $idPiquet){
                    return -1;
                }
            }
        }
            
        $piquet = $this->manager->getRepository(Piquet::class)->findOneById($idPiquet);
        if(!isset($piquet)){
            $this->createEsc(2, $idPiquet, $idMaitre);
        }
         
        $donneesPiquet = new DonneesPiquet;
        $donneesPiquet->setIdPiquet($this->manager->getRepository(Piquet::class)->findOneById($idPiquet));
        $donneesPiquet->setTemperature($temperature);
        $donneesPiquet->setHorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        $donneesPiquet->setLatitude($latitude);
        $donneesPiquet->setLongitude($longitude);
        $donneesPiquet->setHumidite($humidite);
        $donneesPiquet->setBatterie($batterie);
        
        return $donneesPiquet;
    } 
    
    private function InputArmoire($inputTrameArmoire) {
        // ==================== TRAME ARMOIRE ==================
        // [type;id;etat;ip;batterie]
        
        // Trame de 5 données pour l'Armoire
        if(count($inputTrameArmoire) !== 5) return -1; // Trame pas complète renvoie -1
        
        $idArmoire = hexdec($inputTrameArmoire[1]);
        $etat = $inputTrameArmoire[2];
        $ipArmoire = $inputTrameArmoire[3];
        $batterie =  $inputTrameArmoire[4];

        
//         $result = $this->manager->getRepository(DonneesArmoire::class)->findByhorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        
//         if($result){
//             foreach($result as $val){
//                 if($val->getIdArmoire()->getId() === $idArmoire){
//                     return -1;
//                 }
//             }
//         }
        
        $armoire = $this->manager->getRepository(Armoire::class)->findOneById($idArmoire);
        //dump($armoire);
        if(isset($armoire)) {
            $armoire->setEtat(intval($etat));
            $armoire->setIp($ipArmoire);
            $this->manager->persist($armoire);
            $this->manager->flush();
        } else {
            $this->createEsc(3, $idArmoire, null, $ipArmoire, intval($etat));
        }
        
        $donneesArmoire = new DonneesArmoire;
        $donneesArmoire->setIdArmoire($this->manager->getRepository(Armoire::class)->findOneById($idArmoire));
        $donneesArmoire->setBatterie($batterie);
        
        return $donneesArmoire;
    }
}
