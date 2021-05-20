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
     * @ORM\ManyToMany(targetEntity=Piquet::class)
     */
    private $idPiquets;

    /**
     * @ORM\ManyToMany(targetEntity=ElectroVanne::class)
     */
    private $idElectrovanne;

    /**
     * @ORM\ManyToMany(targetEntity=Station::class)
     */
    private $idStation;

    public function __construct()
    {
        $this->idOperateur = new ArrayCollection();
        $this->idPiquets = new ArrayCollection();
        $this->idElectrovanne = new ArrayCollection();
        $this->idStation = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getIdElectrovanne(): Collection
    {
        return $this->idElectrovanne;
    }

    public function addIdElectrovanne(electrovanne $idElectrovanne): self
    {
        if (!$this->idElectrovanne->contains($idElectrovanne)) {
            $this->idElectrovanne[] = $idElectrovanne;
        }

        return $this;
    }

    public function removeIdElectrovanne(electrovanne $idElectrovanne): self
    {
        $this->idElectrovanne->removeElement($idElectrovanne);

        return $this;
    }

    /**
     * @return Collection|Station[]
     */
    public function getIdStation(): Collection
    {
        return $this->idStation;
    }

    public function addIdStation(Station $idStation): self
    {
        if (!$this->idStation->contains($idStation)) {
            $this->idStation[] = $idStation;
        }

        return $this;
    }

    public function removeIdStation(Station $idStation): self
    {
        $this->idStation->removeElement($idStation);

        return $this;
    }
}
