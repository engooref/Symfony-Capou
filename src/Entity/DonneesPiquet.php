<?php

namespace App\Entity;

use App\Repository\DonneesPiquetRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=DonneesPiquetRepository::class)
 * 
 */
class DonneesPiquet implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $horodatage;

    /**
     * @ORM\Column(type="array")
     */
    private $humidite = [];

    /**
     * @ORM\Column(type="float")
     */
    private $temperature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gps;

    /**
     * @ORM\ManyToOne(targetEntity=Piquet::class, inversedBy="idDonneesPiquet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idPiquet;

    /**
     * @ORM\Column(type="smallint")
     */
    private $batterie;

    public function JsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'horodatage' => $this->getHorodatage()->format("Y-m-d H:i:s"),
            'humidite' => $this->getHumidite(),
            'temperature' => $this->getTemperature(),
            'gps' => $this->getGps(),
            'batterie' => $this->getBatterie(),
            
        );
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHorodatage(): ?\DateTimeInterface
    {
        return $this->horodatage;
    }

    public function setHorodatage(\DateTimeInterface $Horodatage): self
    {
        $this->horodatage = $Horodatage;

        return $this;
    }

    public function getHumidite(): ?array
    {
        return $this->humidite;
    }

    public function setHumidite(array $Humidite): self
    {
        $this->humidite = $Humidite;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

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

    public function getIdPiquet(): ?Piquet
    {
        return $this->idPiquet;
    }

    public function setIdPiquet(?Piquet $idPiquet): self
    {
        $this->idPiquet = $idPiquet;

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
}
