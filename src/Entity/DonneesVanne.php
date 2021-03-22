<?php

namespace App\Entity;

use App\Repository\DonneesVanneRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DonneesVanneRepository::class)
 */
class DonneesVanne
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
     * @ORM\Column(type="string", length=255)
     */
    private $gps;

    /**
     * @ORM\Column(type="datetime")
     */
    private $horodatage;

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

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(string $GPS): self
    {
        $this->gps = $GPS;

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
