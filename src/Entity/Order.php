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
     * @ORM\Column(type="date")
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idMollie;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $Price;

    /**
     * @ORM\OneToOne(targetEntity=Adress::class, inversedBy="ord_er", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $adress;


    /**
     * @ORM\Column(type="boolean")
     */
    private $giftCard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentGiftCard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paymentType;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Orders")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Delivery::class, inversedBy="OrderId", cascade={"persist", "remove"})
     */
    private $Delivery;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $invoiceNumber;

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

     /**
    * 
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     *
     * @return  self
     */ 
    public function setCreatedAt(\DateTimeInterface $createdAt)
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

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(string $clientEmail): self
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }

    public function getIdMollie(): ?string
    {
        return $this->idMollie;
    }

    public function setIdMollie(string $idMollie): self
    {
        $this->idMollie = $idMollie;

        return $this;
    }

  

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(Adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getGiftCard(): ?bool
    {
        return $this->giftCard;
    }

    public function setGiftCard(bool $giftCard): self
    {
        $this->giftCard = $giftCard;

        return $this;
    }

    public function getCommentGiftCard(): ?string
    {
        return $this->commentGiftCard;
    }

    public function setCommentGiftCard(?string $commentGiftCard): self
    {
        $this->commentGiftCard = $commentGiftCard;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of Price
     */ 
    public function getPrice()
    {
        return $this->Price;
    }

    /**
     * Set the value of Price
     *
     * @return  self
     */ 
    public function setPrice($Price)
    {
        $this->Price = $Price;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->Delivery;
    }

    public function setDelivery(?Delivery $Delivery): self
    {
        $this->Delivery = $Delivery;

        return $this;
    }

    public function getInvoiceNumber(): ?int
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?int $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

   

   
}
