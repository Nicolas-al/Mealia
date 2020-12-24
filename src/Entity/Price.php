<?php

namespace App\Entity;

use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceRepository::class)
 */
class Price
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
    private $TTC;

    /**
     * @ORM\Column(type="integer")
     */
    private $TVA;

    /**
     * @ORM\Column(type="integer")
     */
    private $HT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTTC(): ?int
    {
        return $this->TTC;
    }

    public function setTTC(int $TTC): self
    {
        $this->TTC = $TTC;

        return $this;
    }

    public function getTVA(): ?int
    {
        return $this->TVA;
    }

    public function setTVA(int $TVA): self
    {
        $this->TVA = $TVA;

        return $this;
    }

    public function getHT(): ?int
    {
        return $this->HT;
    }

    public function setHT(int $HT): self
    {
        $this->HT = $HT;

        return $this;
    }
}
