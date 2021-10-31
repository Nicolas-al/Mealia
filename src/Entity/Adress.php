<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdressRepository::class)
 */
class Adress
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
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $adressSupplement;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     * )
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="adress")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, mappedBy="adress", cascade={"persist", "remove"})
     */
    private $ord_er;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getAdressSupplement(): ?string
    {
        return $this->adressSupplement;
    }

    public function setAdressSupplement(?string $adressSupplement): self
    {
        $this->adressSupplement = $adressSupplement;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

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
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function __toString()
    {
        $format = "Address (id: %s, number:%s, street: %s, building: %s, city: %s, country: %s)";
        return sprintf($format, $this->id, $this->number, $this->street, $this->building, $this->zipCode, $this->city, $this->country);
    }

    public function getOrdEr(): ?Order
    {
        return $this->ord_er;
    }

    public function setOrdEr(Order $ord_er): self
    {
        $this->ord_er = $ord_er;

        // set the owning side of the relation if necessary
        if ($ord_er->getAdress() !== $this) {
            $ord_er->setAdress($this);
        }

        return $this;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }


}
