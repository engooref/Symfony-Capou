<?php

namespace App\Controller;

use App\Entity\Piquet;
use App\Entity\Station; 
use App\Entity\ElectroVanne;
use App\Entity\DonneesStation;
use App\Entity\DonneesPiquet;
use App\Entity\DonneesVanne;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;



class IoController extends AbstractController
{
    #[Route('/control', name: 'control')]
    public function control() : Response {
        return $this->render("control/index.html.twig");
    }
    
    #[Route('/ping', name: 'ping')]
    public function ping() : Response {
        return new Response(Response::HTTP_OK);
    }
        
    #[Route('/pearing', name: 'pearing')]
    public function pearing($type=null, $id=null) {
        
        if(isset($_GET['type'], $_GET['id'])){
            $idObj = hexdec($_GET['id']);
            $typeObj = $_GET['type'];
            
        } else if (isset($type, $id)){
            $idObj = $id;
            $typeObj = $type;
        
        }else 
            return new Response(Response::HTTP_NOT_FOUND);
        
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
        
        $newObj->setId($idObj);
        $newObj->setEtat(True);
        
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($newObj);
        $doctrine->flush();
        
        return new Response(Response::HTTP_OK);
    }
    
    #[Route('/input', name: 'input')]
    public function input() : Response
    {
        if(isset($_GET['Id'], $_GET['Gps'], $_GET['Time']))
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
         
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($newData);
        $doctrine->flush();
        return new Response(Response::HTTP_OK);

    }
    
    #[Route('/extract', name: 'extract')]
    public function extractEntity()
    {
        $doctrine = $this->getDoctrine()->getManager();
        
        $piquet = $doctrine->getRepository(Piquet::class)->findAll();
        $station = $doctrine->getRepository(Station::class)->findAll();
        $electrovanne = $doctrine->getRepository(ElectroVanne::class)->findAll();
    
        return new JsonResponse(array("piquet" => $piquet,
                                      "station" => $station,
                                      "electrovanne" => $electrovanne));
    }
    
    private function InputPiq() {
        
        $idPiq = hexdec($_GET['Id']);
        $gps = $_GET['Gps'];
        $hum = explode(":", $_GET['Hum'], 4);
        $temp = $_GET['Temp'];
        $temps = $_GET['Time'];
        
        $doctrine = $this->getDoctrine()->getManager();
        $Piquet = $doctrine->getRepository(Piquet::class)
                           ->findOneById($idPiq);
        
        if(!isset($Piquet)){
            $this->pearing(0, $idPiq);
        }
        $donneesPiq = new DonneesPiquet;
        $donneesPiq->setGps($gps);
        $donneesPiq->setHumidite($hum);
        $donneesPiq->setTemperature($temp);
        $donneesPiq->setHorodatage(date_create_from_format("H:i:s", $temps));
        $donneesPiq->setIdPiquet($doctrine->getRepository(Piquet::class)
                                           ->findOneById($idPiq));
        
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
            $this->pearing(1, $idSta);
        }
        
        $donneesSta = new DonneesStation;
        $donneesSta->setGps($gps);
        $donneesSta->setPression($press);
        $donneesSta->setHorodatage(date_create_from_format("H:i:s", $temps));
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
            $this->pearing(2, $idVan);
        }
        
        $donneesVan = new DonneesVanne;
        $donneesVan->setGps($gps);
        $donneesVan->setDebit($deb);
        $donneesVan->setHorodatage(date_create_from_format("H:i:s", $temps));
        $donneesVan->setIdVanne($doctrine->getRepository(ElectroVanne::class)
            ->findOneById($idVan));
        
        return $donneesVan;
    }
}
