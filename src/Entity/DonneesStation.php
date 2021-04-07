<?php

namespace App\Entity;

use App\Repository\DonneesStationRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=DonneesStationRepository::class)
 */
class DonneesStation implements JsonSerializable
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
    private $pression;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="idDonneesStation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idStation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gps;

    /**
     * @ORM\Column(type="datetime")
     */
    private $horodatage;

    public function JsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'horodatage' => $this->getHorodatage()->format("Y-m-d H:i:s"),
            'gps' => $this->getGps(),
            'pression' => $this->getPression()
            
        );
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPression(): ?float
    {
        return $this->pression;
    }

    public function setPression(float $pression): self
    {
        $this->pression = $pression;

        return $this;
    }

    public function getIdStation(): ?Station
    {
        return $this->idStation;
    }

    public function setIdStation(?Station $idStation): self
    {
        $this->idStation = $idStation;

        return $this;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(string $gps): self
    {
        $this->gps = $gps;

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
}
