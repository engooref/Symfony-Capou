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

use DateTime;
use DateInterval;

class PhysicController extends AbstractController
{    
    #[Route('/controle', name: 'control')]
    public function control() : Response {
        return $this->render("control/index.html.twig");
    }
    
    #[Route('/getData', name: 'getData')]
    public function getData() : Response {
        if(!isset($_GET['id'], $_GET['type'])) return new Response(Response::HTTP_ERROR);
        
        $id = $_GET['id'];
        $type = $_GET['type'];
        $obj = null;
        $doctrine = $this->getDoctrine()->getManager();
        
        switch (intval($type))
        {
            case 0:
                $obj = $doctrine->getRepository(Piquet::class);
                
                break;
            case 1:
                $obj = $doctrine->getRepository(Armoire::class);
                
                break;
            case 2:
                $obj = $doctrine->getRepository(ElectroVanne::class);
                
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
        
    #[Route('/input', name: 'input')]
    public function input() : Response
    {
        // ==================== TRAME ==================
        // M1=type;id;idMaitre;...&M2=type;id;idMaitre;...
        
        // On traite le nombre de module envoy� � partir du GET 
        $array_nb_module = array();   
        for($i = 1; isset($_GET['M'.strval($i)]); $i++){
            array_push($array_nb_module, $_GET['M'.strval($i)]);
        }
        //dump($array_nb_module); die();
        
        // On traite les donn�es du module envoy�es
        $newData = null;
        foreach($array_nb_module as $module) {
            $array_data_module = explode(';', $module);
            $type = $array_data_module[0];  // Length : 0 -> type
            // 0 -> Armoire | 1 -> Centrale | 2 -> Electrovanne | 3 -> Piquet
            switch (intval($type))
            {
                case 0:
                    $this->InputArmoire($array_data_module);
                    break;
                case 1:
                    $this->InputCentrale($array_data_module);
                    break;
                case 2:
                    $this->InputElectrovanne($array_data_module);
                    break;
                case 3:
                    $newData = $this->InputPiquet($array_data_module);
                    break;
                default:
                    return new Response(Response::HTTP_NOT_FOUND);
            }
            if($newData) {
                $doctrine = $this->getDoctrine()->getManager();
                $doctrine->persist($newData);
                $doctrine->flush();
            }
        }   
        return new Response(Response::HTTP_OK);
    }
    
    #[Route('/mapsControl', name: 'mapsControl')]
    public function mapsControl()
    {
        $doctrine = $this->getDoctrine()->getManager();

        $groupe = $this->getUser()->getIdGroupe();
        $piquetDb = $groupe->getIdPiquets();
        $armoireDb = $groupe->getIdArmoires();
        $electrovanneDb = $groupe->getIdElectrovannes();
        
        $piquet = array();
        $armoire = array();
        $electroVanne = array();
        
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
        
       return new JsonResponse(array("0" => $piquet, "1" => $armoire, "2" => $electroVanne));
       
    }
    
    private function createEsc($type=null, $id=null, $idCen=null, $ipCen=null) {
        
        if(!isset($type) && !isset($id) && !isset($idCen) && !isset($ipCen)) {
            return -1;
        }

        $newObj = null;
        $doctrine = $this->getDoctrine()->getManager();
        
        // 0 -> Armoire | 1 -> Centrale | 2 -> Electrovanne | 3 -> Piquet
        switch (intval($type))
        {
            case 0:
                $newObj = new Armoire;
                $newObj->setId($id);
                $newObj->setEtat(True);
                break;    
            case 1:
                $newObj = new Centrale;
                $newObj->setId($id);
                $newObj->setIp($ipCen);
                break;    
            case 2:
                $newObj = new ElectroVanne;
                $newObj->setId($id);
                $newObj->setEtat(True);
                $newObj->setIdCentrale($doctrine->getRepository(ElectroVanne::class)->findOneById($idCen));
                break;    
            case 3:
                $newObj = new Piquet;
                $newObj->setId($id);
                $newObj->setEtat(True);
                $newObj->setIdCentrale($doctrine->getRepository(Centrale::class)->findOneById($idCen));
                break;
                  
        }

        $doctrine->persist($newObj);
        $doctrine->flush();
        
        return 0;
    }
    
    
    private function InputPiquet($inputTramePiquet) {
        // ==================== TRAME PIQUET ==================
        // [type;id;idMaitre;etat;batterie;horodate;temperature;longitude;latitude;[humidite1:humidite2:humidite3:...]]
        
        dump($inputTramePiquet);
        
        $idPiquet = hexdec($inputTramePiquet[1]);
        $idCentrale = hexdec($inputTramePiquet[2]);
        $batterie =  $inputTramePiquet[3];
        $horodatage = $inputTramePiquet[4];
        $temperature = $inputTramePiquet[5];
        $longitude = $inputTramePiquet[6];
        $latitude = $inputTramePiquet[7];
        $humidite = explode(':', $inputTramePiquet[8]);
        
        $doctrine = $this->getDoctrine()->getManager();
        $result = $doctrine->getRepository(DonneesPiquet::class)->findByhorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        
        if($result){
            foreach($result as $val){
                if($val->getIdPiquet()->getId() === $idPiquet){
                    return -1;
                }
            }
        }
            
         $piquet = $doctrine->getRepository(Piquet::class)->findOneById($idPiquet);
       
         if(!isset($piquet)){
             $this->createEsc(3, $idPiquet, $idCentrale, null);
         }
         
        $donneesPiquet = new DonneesPiquet;
        $donneesPiquet->setIdPiquet($doctrine->getRepository(Piquet::class)->findOneById($idPiquet));
        $donneesPiquet->setHorodatage(date_create_from_format("d-m-Y H:i:s", $horodatage));
        $donneesPiquet->setHumidite($humidite);
        $donneesPiquet->setTemperature($temperature);
        $donneesPiquet->setBatterie($batterie);
        $donneesPiquet->setLatitude($latitude);
        $donneesPiquet->setLongitude($longitude);
        
        return $donneesPiquet;
    }
    
    private function InputElectrovanne($inputTrameElectrovanne) {
        dump($inputTrameElectrovanne);
        
//         $idSta = hexdec($_GET['Id']);
//         $gps = $_GET['Gps'];
//         $temps = $_GET['Time'];
//         $press = $_GET['Press'];
        
//         $doctrine = $this->getDoctrine()->getManager();
//         $Armoire = $doctrine->getRepository(Armoire::class)
//                             ->findOneById($idSta);
        
//         if(!isset($Armoire)){
//             $this->createEsc(1, $idSta);
//         }
        
//         $donneesSta = new DonneesArmoire;
//         $donneesSta->setGps($gps);
//         $donneesSta->setPression($press);
//         $donneesSta->setHorodatage(date_create_from_format("d:m:Y:H:i:s", $temps));
//         $donneesSta->setIdArmoire($doctrine->getRepository(Armoire::class)
//                                            ->findOneById($idSta));
        
//         return $donneesSta;
    }
    
    private function InputCentrale($inputTrameCentrale) {
        dump($inputTrameCentrale);
        
        $idCentrale = hexdec($inputTrameCentrale[1]);
        $ipCentrale = $inputTrameCentrale[2];

        $doctrine = $this->getDoctrine()->getManager();
        $centrale = $doctrine->getRepository(Centrale::class)->findOneById($idCentrale);
        
         if(!isset($centrale)){
             $this->createEsc(1, $idCentrale, null, $ipCentrale);
         }
    }
    private function InputArmoire($inputTrameArmoire) {
        dump($inputTrameArmoire);
        
        $idArmoire = hexdec($inputTrameArmoire[1]);
        
        $doctrine = $this->getDoctrine()->getManager();
        $armoire = $doctrine->getRepository(Armoire::class)->findOneById($idCentrale);
        
        if(!isset($armoire)){
            $this->createEsc(0, $idArmoire, null, null);
        }
    }
    
    #[Route('/getDataPiquet', name: 'getDataPiquet')]
    public function getDataPiquet() : Response {

        $doctrine = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class);
        
        $obj = $doctrine->findBy([],['horodatage' => 'asc']);
        foreach($obj as $article)
        {
            $id[] = $article->getId();
            $Horodatage = $article->getHorodatage();
            $horodatage[] = $Horodatage->format('Y/m/d H:i:s');
            $temp[] = $article->getTemperature();
            $humi[] = $article->getHumidite();
        }
        return new JsonResponse(array("Id" => $id, "Heure" => $horodatage, "Temp"=> $temp, "Humi" => $humi));
    }
}
