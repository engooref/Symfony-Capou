<?php

namespace App\Entity;

use App\Repository\ElectroVanneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElectroVanneRepository::class)
 */
class ElectroVanne
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
     * @ORM\OneToMany(targetEntity=DonneesVanne::class, mappedBy="idVanne", orphanRemoval=true)
     */
    private $idDonneesVanne;

    public function __construct()
    {
        $this->idDonneesVanne = new ArrayCollection();
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
     * @return Collection|DonneesVanne[]
     */
    public function getIdDonneesVanne(): Collection
    {
        return $this->idDonneesVanne;
    }

    public function addIdDonneesVanne(DonneesVanne $idDonneesVanne): self
    {
        if (!$this->idDonneesVanne->contains($idDonneesVanne)) {
            $this->idDonneesVanne[] = $idDonneesVanne;
            $idDonneesVanne->setIdVanne($this);
        }

        return $this;
    }

    public function removeIdDonneesVanne(DonneesVanne $idDonneesVanne): self
    {
        if ($this->idDonneesVanne->removeElement($idDonneesVanne)) {
            // set the owning side to null (unless already changed)
            if ($idDonneesVanne->getIdVanne() === $this) {
                $idDonneesVanne->setIdVanne(null);
            }
        }

        return $this;
    }
}

