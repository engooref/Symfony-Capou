<?php

namespace App\Entity;

use App\Repository\CentraleRepository;
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
     * @ORM\Column(type="boolean")
     */
    private $connected;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getConnected(): ?bool
    {
        return $this->connected;
    }

    public function setConnected(bool $connected): self
    {
        $this->connected = $connected;

        return $this;
    }
}
