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
     * @ORM\ManyToOne(targetEntity=Electrovanne::class, inversedBy="idDonneesVanne")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idVanne;

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

    public function getIdVanne(): ?Electrovanne
    {
        return $this->idVanne;
    }

    public function setIdVanne(?Electrovanne $idVanne): self
    {
        $this->idVanne = $idVanne;

        return $this;
    }
}
