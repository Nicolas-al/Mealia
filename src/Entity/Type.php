<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
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
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="type")
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="type")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=ProductCollection::class, mappedBy="type")
     */
    private $productCollections;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->productCollections = new ArrayCollection();
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

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getType() === $this) {
                $product->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setType($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getType() === $this) {
                $category->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductCollection[]
     */
    public function getProductCollections(): Collection
    {
        return $this->productCollections;
    }

    public function addProductCollection(ProductCollection $productCollection): self
    {
        if (!$this->productCollections->contains($productCollection)) {
            $this->productCollections[] = $productCollection;
            $productCollection->setType($this);
        }

        return $this;
    }

    public function removeProductCollection(ProductCollection $productCollection): self
    {
        if ($this->productCollections->removeElement($productCollection)) {
            // set the owning side to null (unless already changed)
            if ($productCollection->getType() === $this) {
                $productCollection->setType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
