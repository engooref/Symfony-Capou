<?php

namespace App\Entity;

use App\Repository\ParcelleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParcelleRepository::class)
 */
class Parcelle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Operateur::class, mappedBy="idParcelle")
     */
    private $idOperateurs;

    /**
     * @ORM\OneToMany(targetEntity=Piquet::class, mappedBy="idParcelle")
     */
    private $idPiquets;

    /**
     * @ORM\OneToMany(targetEntity=ElectroVanne::class, mappedBy="idParcelle")
     */
    private $idElectrovannes;

    /**
     * @ORM\OneToMany(targetEntity=Armoire::class, mappedBy="idParcelle")
     */
    private $idArmoires;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    public function __construct()
    {
        $this->idOperateurs = new ArrayCollection();
        $this->idPiquets = new ArrayCollection();
        $this->idElectrovannes = new ArrayCollection();
        $this->idArmoires = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(?Parcelle $id): self
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return Collection|Operateur[]
     */
    public function getIdOperateurs(): Collection
    {
        return $this->idOperateurs;  
    }

    public function addIdOperateur(operateur $idOperateur): self
    {
        if (!$this->idOperateurs->contains($idOperateur)) {
            $this->idOperateurs[] = $idOperateur;
            $idOperateur->setIdParcelle($this);
        }

        return $this;
    }

    public function removeIdOperateur(operateur $idOperateur): self
    {
        if ($this->idOperateurs->removeElement($idOperateur)) {
            // set the owning side to null (unless already changed)
            if ($idOperateur->getIdParcelle() === $this) {
                $idOperateur->setIdParcelle(null);
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
