<?php

namespace App\Entity;

use App\Repository\ElectroVanneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=ElectroVanneRepository::class)
 */
class ElectroVanne implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;
    
    /**
     * @ORM\OneToMany(targetEntity=DonneesVanne::class, mappedBy="idVanne", orphanRemoval=true)
     */
    private $idDonneesVanne;

    
    /**
     * @ORM\ManyToOne(targetEntity=Parcelle::class, inversedBy="idElectroVannes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idParcelle;
    
    /**
     * @ORM\OneToMany(targetEntity=Piquet::class, mappedBy="$idMaitreRadio", orphanRemoval=true)
     */
    private $idPiquet;

    public function __construct()
    {
        $this->idDonneesVanne = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getId();
    }
    
    public function JsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'etat' => $this->getEtat()
        );
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    
    public function getIp(): ?string
    {
        return $this->ip;
    }
    
    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }  

    /**
     * @return Collection|DonneesVanne[]
     */
    public function getIdDonnees(): Collection
    {
        return $this->idDonneesVanne;
    }

    public function addIdDonnees(DonneesVanne $idDonneesVanne): self
    {
        if (!$this->idDonneesVanne->contains($idDonneesVanne)) {
            $this->idDonneesVanne[] = $idDonneesVanne;
            $idDonneesVanne->setIdVanne($this);
        }

        return $this;
    }

    public function removeIdDonnees(DonneesVanne $idDonneesVanne): self
    {
        if ($this->idDonneesVanne->removeElement($idDonneesVanne)) {
            // set the owning side to null (unless already changed)
            if ($idDonneesVanne->getIdVanne() === $this) {
                $idDonneesVanne->setIdVanne(null);
            }
        }

        return $this;
    }

    public function getIdParcelle(): ?Parcelle
    {
        return $this->idParcelle;
    }
    
    public function setIdParcelle(?Parcelle $idParcelle): self
    {
        $this->idParcelle = $idParcelle;
        
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

