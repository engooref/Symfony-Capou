<?php

namespace App\Entity;

use App\Repository\EspTestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EspTestRepository::class)
 */
class EspTest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $longitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $latitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongitude(): ?int
    {
        return $this->longitude;
    }

    public function setLongitude(int $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?int
    {
        return $this->latitude;
    }

    public function setLatitude(int $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }
}
