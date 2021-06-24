<?php

namespace App\Entity;

use App\Repository\DonneesVanneRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=DonneesVanneRepository::class)
 */
class DonneesVanne implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $horodatage;
    
    /**
     * @ORM\Column(type="smallint")
     */
    private $batterie;
    
    /**
     * @ORM\ManyToOne(targetEntity=ElectroVanne::class, inversedBy="idDonneesVanne")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idVanne;
    
    public function __toString()
    {
        return $this->getId();
    }

    public function JsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(), 
            'horodatage' => $this->getHorodatage()->format("Y-m-d H:i:s"),
            'batterie' => $this->getBatterie(),
        );
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }
    
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        
        return $this;
    }
    
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
    
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        
        return $this;
    }

    public function getHorodatage(): ?\DateTimeInterface
    {
        return $this->horodatage;
    }

    public function setHorodatage(\DateTimeInterface $horodatage): self
    {
        $this->horodatage = $horodatage;

        return $this;
    }
    
    public function getBatterie(): ?int
    {
        return $this->batterie;
    }
    
    public function setBatterie(int $batterie): self
    {
        $this->batterie = $batterie;
        
        return $this;
    }
    
    public function getIdVanne(): ?ElectroVanne
    {
        return $this->idVanne;
    }
    
    public function setIdVanne(?ElectroVanne $idVanne): self
    {
        $this->idVanne = $idVanne;
        
        return $this;
    }
}
