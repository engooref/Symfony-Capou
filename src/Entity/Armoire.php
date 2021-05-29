<?php

namespace App\Entity;

use App\Repository\ArmoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=ArmoireRepository::class)
 */
class Armoire implements JsonSerializable
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
     * @ORM\OneToMany(targetEntity=DonneesArmoire::class, mappedBy="idArmoire", orphanRemoval=true)
     */
    private $idDonneesArmoire;

    public function __construct()
    {
        $this->idDonneesArmoire = new ArrayCollection();
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
     * @return Collection|DonneesArmoire[]
     */
    public function getIdDonnees(): Collection
    {
        return $this->idDonneesArmoire;
    }

    public function addIdDonnees(DonneesArmoire $idDonneesArmoire): self
    {
        if (!$this->idDonneesArmoire->contains($idDonneesArmoire)) {
            $this->idDonneesArmoire[] = $idDonneesArmoire;
            $idDonneesArmoire->setIdArmoire($this);
        }

        return $this;
    }

    public function removeIdDonnees(DonneesArmoire $idDonneesArmoire): self
    {
        if ($this->idDonneesArmoire->removeElement($idDonneesArmoire)) {
            // set the owning side to null (unless already changed)
            if ($idDonneesArmoire->getIdArmoire() === $this) {
                $idDonneesArmoire->setIdArmoire(null);
            }
        }

        return $this;
    }
}
