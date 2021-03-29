<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
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
    private $orderNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientFirstName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=ProductsOrdered::class, mappedBy="orderNumber")
     */
    private $productsOrdereds;

    public function __construct()
    {
        $this->productsOrdereds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getClientFirstName(): ?string
    {
        return $this->clientFirstName;
    }

    public function setClientFirstName(string $clientFirstName): self
    {
        $this->clientFirstName = $clientFirstName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|ProductsOrdered[]
     */
    public function getProductsOrdereds(): Collection
    {
        return $this->productsOrdereds;
    }

    public function addProductsOrdered(ProductsOrdered $productsOrdered): self
    {
        if (!$this->productsOrdereds->contains($productsOrdered)) {
            $this->productsOrdereds[] = $productsOrdered;
            $productsOrdered->setOrderNumber($this);
        }

        return $this;
    }

    public function removeProductsOrdered(ProductsOrdered $productsOrdered): self
    {
        if ($this->productsOrdereds->removeElement($productsOrdered)) {
            // set the owning side to null (unless already changed)
            if ($productsOrdered->getOrderNumber() === $this) {
                $productsOrdered->setOrderNumber(null);
            }
        }

        return $this;
    }
}
