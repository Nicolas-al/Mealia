<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\CollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=CollectionRepository::class)
 * @UniqueEntity("name")
 */
class ProductCollection
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
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $zeroWaste = false;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="collection")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            // $product->setCollection($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCollection() === $this) {
                $product->setCollection(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of zeroWaste
     */ 
    public function getZeroWaste()
    {
        return $this->zeroWaste;
    }

    /**
     * Set the value of zeroWaste
     *
     * @return  self
     */ 
    public function setZeroWaste($zeroWaste)
    {
        $this->zeroWaste = $zeroWaste;

        return $this;
    }
}
