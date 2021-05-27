<?php

namespace App\Controller;

use App\Entity\Piquet;
use App\Entity\Station; 
use App\Entity\ElectroVanne;
use App\Entity\Centrale;

use App\Entity\DonneesStation;
use App\Entity\DonneesPiquet;
use App\Entity\DonneesVanne;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use DateTime;
use DateInterval;

class IoController extends AbstractController
{
    
    #[Route('/PingCen', name: 'PingCen')]
    public function PingCen() : Response {
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->getConnection()->exec("SELECT horodatage FROM donnees_piquet ORDER BY horodatage");
        $doctrine->flush();
        
        //$Centrale = $doctrine->getRepository(Centrale::class)->finAll();
        
        die();
    }
    
    #[Route('/control', name: 'control')]
    public function control() : Response {
        return $this->render("control/index.html.twig");
    }
    
    #[Route('/getData', name: 'getData')]
    public function getData() : Response {
        if(!isset($_GET['id'], $_GET['type']))
            return new Response(Response::HTTP_ERROR);
        
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
                $obj = $doctrine->getRepository(Station::class);
                
                break;
            case 2:
                $obj = $doctrine->getRepository(ElectroVanne::class);
                
                break;
        }
        
        $obj = $obj->findOneById($id);
        $dataObj = $obj->getIdDonnees()->getValues();

        $dateMax = DateTime::createFromInterface(end($dataObj)->getHorodatage());
        $dateMin = DateTime::createFromInterface(end($dataObj)->getHorodatage());
        $dateMin->sub(new DateInterval("P1W"));
        
        
        $dataCount = count($dataObj);
        
        for($i = 0; $i < $dataCount; $i++){
            $dateObj = $dataObj[$i]->getHorodatage();
            if(!(($dateObj->diff($dateMin)->invert) && !($dateObj->diff($dateMax)->invert)))
                unset($dataObj[$i]);
            
        }
        
        return new JsonResponse(array("Object" => $obj,
                                      "Data" => $dataObj));
    }
    
    
    #[Route('/ping', name: 'ping')]
    public function ping() : Response {
        return new Response(Response::HTTP_OK);
    }
        
    #[Route('/input', name: 'input')]
    public function input() : Response
    {
        if(isset($_GET['Id'], $_GET['Gps'], $_GET["Bat"], $_GET['Time'], $_GET['IdCen']))
            if(isset($_GET['Hum'], $_GET['Temp']))
                $newData = $this->InputPiq();
            else if(isset($_GET['Deb']))
                $newData = $this->InputVan();
            else if(isset($_GET['Press']))
                $newData = $this->InputSta();
            else 
                return new Response(Response::HTTP_I_AM_A_TEAPOT);
        else
            return new Response(Response::HTTP_I_AM_A_TEAPOT);
         
        if($newData) {
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($newData);
            $doctrine->flush();
        }
        return new Response(Response::HTTP_OK);

    }
    
    #[Route('/mapsControl', name: 'mapsControl')]
    public function mapsControl()
    {
        $doctrine = $this->getDoctrine()->getManager();
        
        $piquetDb = $doctrine->getRepository(Piquet::class)->findAll();
        $stationDb = $doctrine->getRepository(Station::class)->findAll();
        $electrovanneDb = $doctrine->getRepository(ElectroVanne::class)->findAll();
        
        $piquet = array();
        $station = array();
        $electroVanne = array();
        
        for($i = 0; $i < count($piquetDb); $i++){
            if($piquetDb[$i]->getEtat()){
                $data = $piquetDb[$i]->getIdDonnees()->GetValues();
                if($data){
                $gpsEnd = end($data)->getGps();
                
                $coordsPiq["id"] = $piquetDb[$i]->getId();
                $coordsPiq["gps"] = $gpsEnd;
                array_push($piquet, $coordsPiq);
                }
            }
        }
        
        for($i = 0; $i < count($stationDb); $i++){
            $data = $stationDb[$i]->getIdDonnees()->GetValues();
            $gpsEnd = end($data)->getGps();
            $coordsSta["id"] = $stationDb[$i]->getId();
            $coordsSta["gps"] = $gpsEnd;
            
        }
        
        for($i = 0; $i < count($electrovanneDb); $i++){
            $data = $electrovanneDb[$i]->getIdDonnees()->GetValues();
            $gpsEnd = end($data)->getGps();
            $coordsVan["id"] = $electrovanneDb[$i]->getId();
            $coordsVan["gps"] = $gpsEnd;
            
        }
        
       return new JsonResponse(array("0" => $piquet,
                                    "1" => $station,
                                    "2" => $electroVanne
                                   ));
       
    }
    
    #[Route('/registerCen', name: 'registerCen')]
    public function registerCen() : Response
    {
        
        if(isset($_GET['Id'], $_GET['Ip']))
            return new Response(Response::HTTP_I_AM_A_TEAPOT);
            
            $newObj = new Centrale;
            
            $newObj->setId(hexdec($_GET['Id']));
            $newObj->setIp($_Get['Ip']);
            
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($newObj);
            $doctrine->flush();
            
            return new Response(Response::HTTP_OK);
            
    }
    
    private function createEsc($type=null, $id=null, $idCen=null) {
        
        if(!isset($type, $id, $idCen))
            return -1;
            
            $newObj = null;
            switch (intval($type))
            {
                case 0:
                    $newObj = new Piquet;
                    break;
                case 1:
                    $newObj = new Station;
                    break;
                case 2:
                    $newObj = new ElectroVanne;
                    break;
            }
            
            $newObj->setId($id);
            $newObj->setEtat(True);
            
            $doctrine = $this->getDoctrine()->getManager();
            $newObj->setIdCentrale($doctrine->getRepository(Centrale::class)->findById($idCen));
            
            $doctrine->persist($newObj);
            $doctrine->flush();
            
            return 0;
    }
    
    
    private function InputPiq() {
        
        $idCen = hexdec($_GET['IdCen']);
        $idPiq = hexdec($_GET['Id']);
        $gps = $_GET['Gps'];
        $bat = $_GET["Bat"];
        $hum = explode(":", $_GET['Hum']);
        $temp = $_GET['Temp'];
        $temps = $_GET['Time'];
        
        $doctrine = $this->getDoctrine()->getManager();
        $result = $doctrine->getRepository(DonneesPiquet::class)->findByhorodatage(date_create_from_format("d:m:Y:H:i:s", $temps));
        
        if($result){
            foreach($result as $val){
                if($val->getIdPiquet()->getId() == $idPiq){
                    return -1;
                }
            }
        }
            
        $piquet = $doctrine->getRepository(Piquet::class)
                           ->findOneById($idPiq);
        
        $centrale = $doctrine->getRepository(Piquet::class)
                             ->findOneById($idCen);
       
        if(!isset($centrale)){
               return -1;
        }
       
        if(!isset($piquet)){
            $this->createEsc(0, $idPiq, $idCen);
        }
        
        $donneesPiq = new DonneesPiquet;
        $donneesPiq->setGps($gps);
        $donneesPiq->setHumidite($hum);
        $donneesPiq->setTemperature($temp);
        $donneesPiq->setHorodatage(date_create_from_format("d:m:Y:H:i:s", $temps));
        $donneesPiq->setIdPiquet($doctrine->getRepository(Piquet::class)
                                           ->findOneById($idPiq));
        $donneesPiq->setBatterie($bat);
        ;
        return $donneesPiq;
    }
    
    private function InputSta() {
        
        $idSta = hexdec($_GET['Id']);
        $gps = $_GET['Gps'];
        $temps = $_GET['Time'];
        $press = $_GET['Press'];
        
        $doctrine = $this->getDoctrine()->getManager();
        $Station = $doctrine->getRepository(Station::class)
                            ->findOneById($idSta);
        
        if(!isset($Station)){
            $this->createEsc(1, $idSta);
        }
        
        $donneesSta = new DonneesStation;
        $donneesSta->setGps($gps);
        $donneesSta->setPression($press);
        $donneesSta->setHorodatage(date_create_from_format("d:m:Y:H:i:s", $temps));
        $donneesSta->setIdStation($doctrine->getRepository(Station::class)
                                           ->findOneById($idSta));
        
        return $donneesSta;
    }
    
    private function InputVan() {
        
        $idVan = hexdec($_GET['Id']);
        $gps = $_GET['Gps'];
        $temps = $_GET['Time'];
        $deb = $_GET['Deb'];
        
        $doctrine = $this->getDoctrine()->getManager();
        $Station = $doctrine->getRepository(ElectroVanne::class)
        ->findOneById($idVan);
        
        if(!isset($Station)){
            $this->createEsc(2, $idVan);
        }
        
        $donneesVan = new DonneesVanne;
        $donneesVan->setGps($gps);
        $donneesVan->setDebit($deb);
        $donneesVan->setHorodatage(date_create_from_format("d:m:Y:H:i:s", $temps));
        $donneesVan->setIdVanne($doctrine->getRepository(ElectroVanne::class)
            ->findOneById($idVan));
        
        return $donneesVan;
    }
    
    #[Route('/getDataPiquet', name: 'getDataPiquet')]
    public function getDataPiquet() : Response {

        $doctrine = $this->getDoctrine()->getManager()->getRepository(DonneesPiquet::class);
        
        $obj = $doctrine->findAll();
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
