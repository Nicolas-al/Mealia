<?php

namespace App\Entity;

use App\Entity\Adress;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $lastName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Adress::class, cascade={"persist", "remove"}, inversedBy="user")
     */
    protected $adress;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="user")
     */
    private $avis;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Sex;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="user")
     */
    private $Orders;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $newsletter;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\OneToMany(targetEntity=GiftCard::class, mappedBy="user")
     */
    private $giftCards;

    /**
     * @ORM\OneToMany(targetEntity=Avoir::class, mappedBy="user")
     */
    private $avoirs;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->Orders = new ArrayCollection();
        $this->giftCards = new ArrayCollection();
        $this->avoirs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $adress ? null : $this;
        if ($adress->getUser() !== $newUser) {
            $adress->setUser($newUser);
        }

        return $this;
    }

    /**
     * Get the value of plainPassword
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get oRM\Column(type="string", length=180)
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set oRM\Column(type="string", length=180)
     *
     * @return  self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get oRM\Column(type="string", length=180)
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set oRM\Column(type="string", length=180)
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function __toString()
    {
        $format = "User (id: %s, firstname: %s, lastname: %s, role: %s, address: %s)\n";
        return sprintf($format, $this->id, $this->firstname, $this->lastname, $this->role, $this->address);
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setUser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->Sex;
    }

    public function setSex(string $Sex): self
    {
        $this->Sex = $Sex;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->Orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->Orders->contains($order)) {
            $this->Orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->Orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(?bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return Collection|GiftCard[]
     */
    public function getGiftCards(): Collection
    {
        return $this->giftCards;
    }

    public function addGiftCard(GiftCard $giftCard): self
    {
        if (!$this->giftCards->contains($giftCard)) {
            $this->giftCards[] = $giftCard;
            $giftCard->setUser($this);
        }

        return $this;
    }

    public function removeGiftCard(GiftCard $giftCard): self
    {
        if ($this->giftCards->removeElement($giftCard)) {
            // set the owning side to null (unless already changed)
            if ($giftCard->getUser() === $this) {
                $giftCard->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Avoir[]
     */
    public function getAvoirs(): Collection
    {
        return $this->avoirs;
    }

    public function addAvoir(Avoir $avoir): self
    {
        if (!$this->avoirs->contains($avoir)) {
            $this->avoirs[] = $avoir;
            $avoir->setUser($this);
        }

        return $this;
    }

    public function removeAvoir(Avoir $avoir): self
    {
        if ($this->avoirs->removeElement($avoir)) {
            // set the owning side to null (unless already changed)
            if ($avoir->getUser() === $this) {
                $avoir->setUser(null);
            }
        }

        return $this;
    }
}
