<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Operateur::class, mappedBy="idGroupe")
     */
    private $idOperateur;

    /**
     * @ORM\OneToMany(targetEntity=Piquet::class, mappedBy="idGroupe")
     */
    private $idPiquets;

    /**
     * @ORM\OneToMany(targetEntity=ElectroVanne::class, mappedBy="idGroupe")
     */
    private $idElectrovannes;

    /**
     * @ORM\ManyToMany(targetEntity=Armoire::class)
     */
    private $idArmoires;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    public function __construct()
    {
        $this->idOperateur = new ArrayCollection();
        $this->idPiquets = new ArrayCollection();
        $this->idElectrovannes = new ArrayCollection();
        $this->idArmoires = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(?Groupe $id): self
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return Collection|Operateur[]
     */
    public function getIdOperateur(): Collection
    {
        return $this->idOperateur;  
    }

    public function addIdOperateur(operateur $idOperateur): self
    {
        if (!$this->idOperateur->contains($idOperateur)) {
            $this->idOperateur[] = $idOperateur;
            $idOperateur->setIdGroupe($this);
        }

        return $this;
    }

    public function removeIdOperateur(operateur $idOperateur): self
    {
        if ($this->idOperateur->removeElement($idOperateur)) {
            // set the owning side to null (unless already changed)
            if ($idOperateur->getIdGroupe() === $this) {
                $idOperateur->setIdGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Piquet[]
     */
    public function getIdPiquets(): Collection
    {
        return $this->idPiquets;
    }

    public function addIdPiquet(Piquet $idPiquet): self
    {
        if (!$this->idPiquets->contains($idPiquet)) {
            $this->idPiquets[] = $idPiquet;
        }

        return $this;
    }

    public function removeIdPiquet(Piquet $idPiquet): self
    {
        $this->idPiquets->removeElement($idPiquet);

        return $this;
    }

    /**
     * @return Collection|Electrovanne[]
     */
    public function getIdElectrovannes(): Collection
    {
        return $this->idElectrovannes;
    }

    public function addIdElectrovanne(electrovanne $idElectrovanne): self
    {
        if (!$this->idElectrovannes->contains($idElectrovanne)) {
            $this->idElectrovannes[] = $idElectrovanne;
        }

        return $this;
    }

    public function removeIdElectrovanne(electrovanne $idElectrovanne): self
    {
        $this->idElectrovannes->removeElement($idElectrovanne);

        return $this;
    }

    /**
     * @return Collection|Armoire[]
     */
    public function getIdArmoires(): Collection
    {
        return $this->idArmoires;
    }

    public function addIdArmoire(Armoire $idArmoire): self
    {
        if (!$this->idArmoires->contains($idArmoire)) {
            $this->idArmoires[] = $idArmoire;
        }

        return $this;
    }

    public function removeIdArmoire(Armoire $idArmoire): self
    {
        $this->idArmoires->removeElement($idArmoire);

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
