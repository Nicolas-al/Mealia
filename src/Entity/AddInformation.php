<?php

namespace App\Entity;

use App\Repository\AddInformationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AddInformationRepository::class)
 */
class AddInformation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dimension;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tissue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(string $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getTissue(): ?string
    {
        return $this->tissue;
    }

    public function setTissue(string $tissue): self
    {
        $this->tissue = $tissue;

        return $this;
    }
}
