<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProductsOrderedRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=ProductsOrderedRepository::class)
 * @Vich\Uploadable
 */
class ProductsOrdered
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
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="productsOrdereds", cascade={"persist"})
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $productId;

     /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

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
     * @ORM\Column(type="integer")
     */
    private $price;


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
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderNumber(): ?Order
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?Order $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

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

    /**
     * Get the value of updatedAt
     *
     * @return  \DateTime
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param  \DateTime  $updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
