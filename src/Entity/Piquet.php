<?php

namespace App\Entity;

use App\Repository\PiquetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=PiquetRepository::class)
 */
class Piquet implements JsonSerializable
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
     * @ORM\OneToMany(targetEntity=DonneesPiquet::class, mappedBy="idPiquet", orphanRemoval=true)
     */
    private $idDonneesPiquet;

    
    /**
     * @ORM\ManyToOne(targetEntity=Parcelle::class, inversedBy="idPiquets")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idParcelle;
    
    /**
     * @ORM\ManyToOne(targetEntity=ElectroVanne::class, inversedBy="idElectroVannes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idMaitreRadio;

    public function __construct()
    {
        $this->idDonneesPiquet = new ArrayCollection();
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
    
    public function setId(?int $id): self
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

    /**
     * @return Collection|donneespiquet[]
     */
    public function getIdDonnees(): Collection
    {
        return $this->idDonneesPiquet;
    }

    public function addIdDonnees(donneespiquet $idDonneesPiquet): self
    {
        if (!$this->idDonneesPiquet->contains($idDonneesPiquet)) {
            $this->idDonneesPiquet[] = $idDonneesPiquet;
            $idDonneesPiquet->setIdPiquet($this);
        }

        return $this;
    }

    public function removeIdDonnees(donneespiquet $idDonneesPiquet): self
    {
        if ($this->idDonneesPiquet->removeElement($idDonneesPiquet)) {
            // set the owning side to null (unless already changed)
            if ($idDonneesPiquet->getIdPiquet() === $this) {
                $idDonneesPiquet->setIdPiquet(null);
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
    
    public function getIdMaitreRadio(): ?ElectroVanne
    {
        return $this->idMaitreRadio;
    }
    
    public function setIdMaitreRadio(?ElectroVanne $idMaitreRadio): self
    {
        $this->idMaitreRadio = $idMaitreRadio;
        
        return $this;
    }  
}
