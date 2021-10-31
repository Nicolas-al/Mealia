<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SizeRepository::class)
 */
class Size
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sizeOne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sizeTwo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sizeThree;

    /**
     * @ORM\OneToOne(targetEntity=Product::class, inversedBy="size", cascade={"persist", "remove"})
     */
    private $product;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockSizeOne;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockSizeTwo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockSizeThree;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceSizeOne;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceSizeTwo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceSizeThree;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSizeOne(): ?string
    {
        return $this->sizeOne;
    }

    public function setSizeOne(?string $sizeOne): self
    {
        $this->sizeOne = $sizeOne;

        return $this;
    }

    public function getSizeTwo(): ?string
    {
        return $this->sizeTwo;
    }

    public function setSizeTwo(?string $sizeTwo): self
    {
        $this->sizeTwo = $sizeTwo;

        return $this;
    }

    public function getSizeThree(): ?string
    {
        return $this->sizeThree;
    }

    public function setSizeThree(?string $sizeThree): self
    {
        $this->sizeThree = $sizeThree;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getStockSizeOne(): ?int
    {
        return $this->stockSizeOne;
    }

    public function setStockSizeOne(?int $stockSizeOne): self
    {
        $this->stockSizeOne = $stockSizeOne;

        return $this;
    }

    public function getStockSizeTwo(): ?int
    {
        return $this->stockSizeTwo;
    }

    public function setStockSizeTwo(?int $stockSizeTwo): self
    {
        $this->stockSizeTwo = $stockSizeTwo;

        return $this;
    }

    public function getStockSizeThree(): ?int
    {
        return $this->stockSizeThree;
    }

    public function setStockSizeThree(?int $stockSizeThree): self
    {
        $this->stockSizeThree = $stockSizeThree;

        return $this;
    }

    public function getPriceSizeOne(): ?int
    {
        return $this->priceSizeOne;
    }

    public function setPriceSizeOne(?int $priceSizeOne): self
    {
        $this->priceSizeOne = $priceSizeOne;

        return $this;
    }

    public function getPriceSizeTwo(): ?int
    {
        return $this->priceSizeTwo;
    }

    public function setPriceSizeTwo(?int $priceSizeTwo): self
    {
        $this->priceSizeTwo = $priceSizeTwo;

        return $this;
    }

    public function getPriceSizeThree(): ?int
    {
        return $this->priceSizeThree;
    }

    public function setPriceSizeThree(?int $priceSizeThree): self
    {
        $this->priceSizeThree = $priceSizeThree;

        return $this;
    }

}
