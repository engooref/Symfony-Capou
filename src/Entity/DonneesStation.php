<?php

namespace App\Entity;

use App\Repository\DonneesStationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DonneesStationRepository::class)
 */
class DonneesStation
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
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="idDonneesStation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idStation;

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

    public function getIdStation(): ?Station
    {
        return $this->idStation;
    }

    public function setIdStation(?Station $idStation): self
    {
        $this->idStation = $idStation;

        return $this;
    }
}
