<?php

namespace App\Entity;

use App\Repository\PiquetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PiquetRepository::class)
 */
class Piquet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=donneespiquet::class, mappedBy="idPiquet", orphanRemoval=true)
     */
    private $idDonneesPiquet;

    public function __construct()
    {
        $this->idDonneesPiquet = new ArrayCollection();
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
    public function getIdDonneesPiquet(): Collection
    {
        return $this->idDonneesPiquet;
    }

    public function addIdDonneesPiquet(donneespiquet $idDonneesPiquet): self
    {
        if (!$this->idDonneesPiquet->contains($idDonneesPiquet)) {
            $this->idDonneesPiquet[] = $idDonneesPiquet;
            $idDonneesPiquet->setIdPiquet($this);
        }

        return $this;
    }

    public function removeIdDonneesPiquet(donneespiquet $idDonneesPiquet): self
    {
        if ($this->idDonneesPiquet->removeElement($idDonneesPiquet)) {
            // set the owning side to null (unless already changed)
            if ($idDonneesPiquet->getIdPiquet() === $this) {
                $idDonneesPiquet->setIdPiquet(null);
            }
        }

        return $this;
    }
}
