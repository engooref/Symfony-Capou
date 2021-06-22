<?php

namespace App\Controller;

use App\Entity\Piquet;
use App\Entity\Armoire;
use App\Entity\ElectroVanne;
use App\Entity\Centrale;

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
        if(!isset($_GET['type'], $_GET['id'])) return new Response(Response::HTTP_ERROR);
        $type = $_GET['type'];
        $id = $_GET['id'];
        $obj = null;
        // 1 -> Armoire | 2 -> Electrovanne | 3 -> Piquet
        switch (intval($type))
        {
            case 1:
                $obj = $this->manager->getRepository(Armoire::class);
                break;
            case 2:
                $obj = $this->manager->getRepository(ElectroVanne::class);
                break;
            case 3:
                $obj = $this->manager->getRepository(Piquet::class);
                break;
        }
        
        $obj = $obj->findOneById($id);
        $dataObj = $obj->getIdDonnees()->getValues();

        $dateMin = (DateTime::createFromInterface(end($dataObj)->getHorodatage()))->sub(new DateInterval("P1W"));
        $dateMax = DateTime::createFromInterface(end($dataObj)->getHorodatage());

        $dataCount = count($dataObj);
        
        for($i = 0; $i < $dataCount; $i++){
            $dateObj = $dataObj[$i]->getHorodatage();
            if(!(($dateObj->diff($dateMin)->invert) && !($dateObj->diff($dateMax)->invert)))
                unset($dataObj[$i]);    
        }
        
        return new JsonResponse(array("Object" => $obj, "Data" => $dataObj));
    }

    #[Route('/getDataPiquet', name: 'getDataPiquet')]
    public function getDataPiquet() : Response {
        $periode = "1sem";

            $periode = $_POST['periode'];


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
        $obj = $dataPiquet->findByDateBetween($datePeriode);

        foreach($obj as $piquet)
        {
            $id[] = $piquet->getId();
            $Horodatage = $piquet->getHorodatage();
            $horodatage[] = $Horodatage->format('Y/m/d H:i:s');
            $temp[] = $piquet->getTemperature();
            $humi[] = $piquet->getHumidite();
        }

        return new JsonResponse(array("Id" => $id, "Heure" => $horodatage, "Temp"=> $temp, "Humi" => $humi));
    }
    
    #[Route('/mapsControl', name: 'mapsControl')]
    public function mapsControl()
    {
        $groupe = $this->getUser()->getIdGroupe();
        $piquetDb = $groupe->getIdPiquets();
        $armoireDb = $groupe->getIdArmoires();
        $electrovanneDb = $groupe->getIdElectrovannes();
        dump($piquetDb, $armoireDb, $electrovanneDb);
        $piquet = array();
        $armoire = array();
        $electrovanne = array();
        
        for($i = 0; $i < count($piquetDb); $i++){
            if($piquetDb[$i]->getEtat()){
                $val = $piquetDb[$i]->getIdDonnees()->GetValues();
                $data = end($val);
                
                $coordsPiq["id"] = $piquetDb[$i]->getId();
                $coordsPiq["gps"] = array("latitude" => $data->getLatitude(), "longitude" => $data->getLongitude());
                array_push($piquet, $coordsPiq);
            }
        }
        
        for($i = 0; $i < count($armoireDb); $i++){
            if($armoireDb[$i]->getEtat()){
                $val = $armoireDb[$i]->getIdDonnees()->GetValues();
                $data = end($val);
                
                $coordsArm["id"] = $armoireDb[$i]->getId();
                $coordsArm["gps"] = array("latitude" => $data->getLatitude(), "longitude" => $data->getLongitude());
                array_push($armoire, $coordsArm);
            }
        }
        
        for($i = 0; $i < count($electrovanneDb); $i++){
            if($electrovanneDb[$i]->getEtat()){
                $val = $electrovanneDb[$i]->getIdDonnees()->GetValues();
                $data = end($val);
                
                $coordsElec["id"] = $electrovanneDb[$i]->getId();
                $coordsElec["gps"] = array("latitude" => $data->getLatitude(), "longitude" => $data->getLongitude());
                array_push($electrovanne, $coordsElec);
            }
        }
        return new JsonResponse(array("1" => $armoire, "2" => $electroVanne, "3" => $piquet));
       
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
            // 0 -> Centrale | 1 -> Armoire | 2 -> Electrovanne | 3 -> Piquet
            switch (intval($type))
            {
                case 0:
                    $this->InputCentrale($array_data_module);
                    break;
                case 1:
                    $newData = $this->InputArmoire($array_data_module);
                    break;
                case 2:
                    $newData = $this->InputElectrovanne($array_data_module);
                    break;
                case 3:
                    $newData = $this->InputPiquet($array_data_module);
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
    
    private function createEsc($type=null, $id=null, $idCen=null, $ipCen=null) {
        
        if(!isset($type) && !isset($id) && !isset($idCen) && !isset($ipCen)) {
            return -1;
        }

        $newObj = null;
        
        // 0 -> Centrale | 1 -> Armoire | 2 -> Electrovanne | 3 -> Piquet
        switch (intval($type))
        {
            case 0:
                $newObj = new Centrale;
                $newObj->setId($id);
                $newObj->setIp($ipCen);
                break;  
            case 1:
                $newObj = new Armoire;
                $newObj->setId($id);
                $newObj->setEtat(True);
                break;  
            case 2:
                $newObj = new ElectroVanne;
                $newObj->setId($id);
                $newObj->setEtat(True);
                $newObj->setIdCentrale($this->manager->getRepository(Centrale::class)->findOneById($idCen));
                break;    
            case 3:
                $newObj = new Piquet;
                $newObj->setId($id);
                $newObj->setEtat(True);
                $newObj->setIdCentrale($this->manager->getRepository(Centrale::class)->findOneById($idCen));
                break;
                  
        }

        $this->manager->persist($newObj);
        $this->manager->flush();
        
        return 0;
    }

    private function InputCentrale($inputTrameCentrale) {
        // ==================== TRAME CENTRALE ==================
        // [type;id;ip]
        
        // Trame de 3 données pour la Centrale
        if(count($inputTrameCentrale) !== 3) return -1; // Trame pas complète renvoie -1
        
        
        $idCentrale = hexdec($inputTrameCentrale[1]);
        $ipCentrale = $inputTrameCentrale[2];
        
        $centrale = $this->manager->getRepository(Centrale::class)->findOneById($idCentrale);
        
        if(!isset($centrale)){
            $this->createEsc(0, $idCentrale, null, $ipCentrale);
        }
    }
    
    private function InputArmoire($inputTrameArmoire) {
        // ==================== TRAME ARMOIRE ==================
        // [type;id;pression;horodate;longitude;latitude]
        
        // Trame de 6 données pour l'Armoire
        if(count($inputTrameArmoire) !== 6) return -1; // Trame pas complète renvoie -1
        
        $idArmoire = hexdec($inputTrameArmoire[1]);
        $pression = $inputTrameArmoire[2];
        $horodatage = $inputTrameArmoire[3];
        $longitude = $inputTrameArmoire[4];
        $latitude = $inputTrameArmoire[5];
        
        $result = $this->manager->getRepository(DonneesArmoire::class)->findByhorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        
        if($result){
            foreach($result as $val){
                if($val->getIdArmoire()->getId() === $idArmoire){
                    return -1;
                }
            }
        }
        
        $armoire = $this->manager->getRepository(Armoire::class)->findOneById($idArmoire);
        
        if(!isset($armoire)){
            $this->createEsc(1, $idArmoire, null, null);
        }
        
        $donneesArmoire = new DonneesArmoire;
        $donneesArmoire->setIdArmoire($this->manager->getRepository(Armoire::class)->findOneById($idArmoire));
        $donneesArmoire->setPression($pression);
        $donneesArmoire->setHorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        $donneesArmoire->setLatitude($latitude);
        $donneesArmoire->setLongitude($longitude);
        
        return $donneesArmoire;
    }
    
    private function InputElectrovanne($inputTrameElectrovanne) {
        // ==================== TRAME ELECTROVANNE ==================
        // [type;id;idMaitre;debit;horodate;longitude;latitude]
        
        // Trame de 7 données pour l'ElectroVanne
        if(count($inputTrameElectrovanne) !== 7) return -1; // Trame pas complète renvoie -1
        
        $idElectroVanne = hexdec($inputTrameElectrovanne[1]);
        $idCentrale = hexdec($inputTrameElectrovanne[2]);
        $debit =  $inputTrameElectrovanne[3];
        $horodatage = $inputTrameElectrovanne[4];
        $longitude = $inputTrameElectrovanne[5];
        $latitude = $inputTrameElectrovanne[6];
        
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
            $this->createEsc(2, $idElectroVanne, $idCentrale, null);
        }
        
        $donneesVanne = new DonneesVanne;
        $donneesVanne->setIdVanne($this->manager->getRepository(ElectroVanne::class)->findOneById($idElectroVanne));
        $donneesVanne->setDebit($debit);
        $donneesVanne->setHorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        $donneesVanne->setLatitude($latitude);
        $donneesVanne->setLongitude($longitude);
        
        return $donneesVanne;
    }
    
    private function InputPiquet($inputTramePiquet) {
        // ==================== TRAME PIQUET ==================
        // [type;id;idMaitre;batterie;horodate;temperature;longitude;latitude;[humidite1:humidite2:humidite3:...]]
        
        // Trame de 9 données pour le Piquet         
        if(count($inputTramePiquet) !== 9) return -1; // Trame pas complète renvoie -1
        
        $idPiquet = hexdec($inputTramePiquet[1]);
        $idCentrale = hexdec($inputTramePiquet[2]);
        $batterie =  $inputTramePiquet[3];
        $horodatage = $inputTramePiquet[4];
        $temperature = $inputTramePiquet[5];
        $longitude = $inputTramePiquet[6];
        $latitude = $inputTramePiquet[7];
        $humidite = explode(':', $inputTramePiquet[8]);
        
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
             $this->createEsc(3, $idPiquet, $idCentrale, null);
         }
         
        $donneesPiquet = new DonneesPiquet;
        $donneesPiquet->setIdPiquet($this->manager->getRepository(Piquet::class)->findOneById($idPiquet));
        $donneesPiquet->setHorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        $donneesPiquet->setHumidite($humidite);
        $donneesPiquet->setTemperature($temperature);
        $donneesPiquet->setBatterie($batterie);
        $donneesPiquet->setLatitude($latitude);
        $donneesPiquet->setLongitude($longitude);
        
        return $donneesPiquet;
    } 
}
