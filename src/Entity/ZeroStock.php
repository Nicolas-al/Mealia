<?php

namespace App\Entity;

use App\Repository\ZeroStockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZeroStockRepository::class)
 */
class ZeroStock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $alertMail = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $productId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlertMail(): ?array
    {
        return $this->alertMail;
    }

    public function setAlertMail(?array $alertMail): self
    {
        $this->alertMail = $alertMail;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }
}
