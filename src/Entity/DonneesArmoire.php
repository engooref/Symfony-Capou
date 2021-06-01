<?php

namespace App\Entity;

use App\Repository\DonneesArmoireRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=DonneesArmoireRepository::class)
 */
class DonneesArmoire implements JsonSerializable
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
     * @ORM\ManyToOne(targetEntity=Armoire::class, inversedBy="idDonneesArmoire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idArmoire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $horodatage;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    public function JsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'horodatage' => $this->getHorodatage()->format("Y-m-d H:i:s"),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
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

    public function getIdArmoire(): ?Armoire
    {
        return $this->idArmoire;
    }

    public function setIdArmoire(?Armoire $idArmoire): self
    {
        $this->idArmoire = $idArmoire;

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
}
