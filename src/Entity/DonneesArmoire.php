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
     * @ORM\Column(type="smallint")
     */
    private $batterie;

    /**
     * @ORM\ManyToOne(targetEntity=Armoire::class, inversedBy="idDonneesArmoire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idArmoire;
    
    public function __toString()
    {
        return $this->getId();
    }

    public function JsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'batterie' => $this->getBatterie(),
        );
    }
    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdArmoire(): ?Armoire
    {
        return $this->idArmoire;
    }

    public function setIdArmoire(?Armoire $idArmoire): self
    {
        $this->idArmoire = $idArmoire;

        return $this;
    }
}
