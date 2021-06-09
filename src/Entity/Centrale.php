<?php

namespace App\Entity;

use App\Repository\CentraleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CentraleRepository::class)
 */
class Centrale
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\OneToMany(targetEntity=Piquet::class, mappedBy="idCentrale")
     */
    private $idPiquets;

    /**
     * @ORM\OneToMany(targetEntity=ElectroVanne::class, mappedBy="idCentrale")
     */
    private $idElectroVannes;

    public function __construct()
    {
        $this->idPiquets = new ArrayCollection();
        $this->idElectroVannes = new ArrayCollection();
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
            $idPiquet->setIdCentrale($this);
        }

        return $this;
    }

    public function removeIdPiquet(Piquet $idPiquet): self
    {
        if ($this->idPiquets->removeElement($idPiquet)) {
            // set the owning side to null (unless already changed)
            if ($idPiquet->getIdCentrale() === $this) {
                $idPiquet->setIdCentrale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ElectroVanne[]
     */
    public function getIdElectroVannes(): Collection
    {
        return $this->$idElectroVannes;
    }

    public function addIdElectroVanne(ElectroVanne $idElectroVanne): self
    {
        if (!$this->idElectroVannes->contains($idElectroVanne)) {
            $this->idElectroVannes[] = $idElectroVanne;
            $idElectroVanne->setIdCentrale($this);
        }

        return $this;
    }

    public function removeIdElectroVanne(ElectroVanne $idElectroVanne): self
    {
        if ($this->idElectroVannes->removeElement($idElectroVanne)) {
            // set the owning side to null (unless already changed)
            if ($idElectroVanne->getIdCentrale() === $this) {
                $idElectroVanne->setIdCentrale(null);
            }
        }

        return $this;
    }
}
