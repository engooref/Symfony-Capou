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
     * @ORM\ManyToOne(targetEntity=Centrale::class, inversedBy="idPiquets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCentrale;

    public function __construct()
    {
        $this->idDonneesPiquet = new ArrayCollection();
    }
    
    public function JsonSerialize() 
    {
        return array(
            'id' => $this->getId(),
            'etat' => $this->getEtat()
        );
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdCentrale(): ?Centrale
    {
        return $this->idCentrale;
    }

    public function setIdCentrale(?Centrale $idCentrale): self
    {
        $this->idCentrale = $idCentrale;

        return $this;
    }
    
    
}
