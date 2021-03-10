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
    private $val;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVal(): ?int
    {
        return $this->val;
    }

    public function setVal(int $val): self
    {
        $this->val = $val;

        return $this;
    }
}
