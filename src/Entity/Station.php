<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 */
class Station implements JsonSerializable
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
     * @ORM\OneToMany(targetEntity=DonneesStation::class, mappedBy="idStation", orphanRemoval=true)
     */
    private $idDonneesStation;

    public function __construct()
    {
        $this->idDonneesStation = new ArrayCollection();
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
     * @return Collection|DonneesStation[]
     */
    public function getIdDonnees(): Collection
    {
        return $this->idDonneesStation;
    }

    public function addIdDonnees(DonneesStation $idDonneesStation): self
    {
        if (!$this->idDonneesStation->contains($idDonneesStation)) {
            $this->idDonneesStation[] = $idDonneesStation;
            $idDonneesStation->setIdStation($this);
        }

        return $this;
    }

    public function removeIdDonnees(DonneesStation $idDonneesStation): self
    {
        if ($this->idDonneesStation->removeElement($idDonneesStation)) {
            // set the owning side to null (unless already changed)
            if ($idDonneesStation->getIdStation() === $this) {
                $idDonneesStation->setIdStation(null);
            }
        }

        return $this;
    }
}
