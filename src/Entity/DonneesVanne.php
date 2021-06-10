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
    private $debit;

    /**
     * @ORM\ManyToOne(targetEntity=ElectroVanne::class, inversedBy="idDonneesVanne")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idVanne;

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
            'debit' => $this->getDebit(),
            'horodatage' => $this->getHorodatage()->format("Y-m-d H:i:s"),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(), 
        );
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebit(): ?float
    {
        return $this->debit;
    }

    public function setDebit(float $debit): self
    {
        $this->debit = $debit;

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
