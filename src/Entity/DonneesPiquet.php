<?php

namespace App\Entity;

use App\Repository\DonneesPiquetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DonneesPiquetRepository::class)
 */
class DonneesPiquet
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
    private $Horodatage;

    /**
     * @ORM\Column(type="array")
     */
    private $Humidite = [];

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHorodatage(): ?\DateTimeInterface
    {
        return $this->Horodatage;
    }

    public function setHorodatage(\DateTimeInterface $Horodatage): self
    {
        $this->Horodatage = $Horodatage;

        return $this;
    }

    public function getHumidite(): ?array
    {
        return $this->Humidite;
    }

    public function setHumidite(array $Humidite): self
    {
        $this->Humidite = $Humidite;

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
}
