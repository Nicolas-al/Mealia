<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable
 */
class Image
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
    private $one;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="one")
     * @var File
     */
    private $oneFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $two;
    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="two")
     * @var File
     */
    private $twoFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $three;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="three")
     * @var File
     */
    private $threeFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $four;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="four")
     * @var File
     */
    private $fourFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setOneFile(File $one = null)
    {
        $this->oneFile = $one;
        if ($one) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getOneFile()
    {
        return $this->oneFile;
    }
    public function getOne(): ?string
    {
        return $this->one;
    }

    public function setOne( ?string $one): self
    {
        $this->one = $one;

        return $this;
    }

    public function getTwo(): ?string
    {
        return $this->two;
    }

    public function setTwo( ?string $two): self
    {
        $this->two = $two;

        return $this;
    }

    public function setTwoFile(File $two = null)
    {
        $this->twoFile = $two;
        if ($two) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getTwoFile()
    {
        return $this->twoFile;
    }

    public function getThree(): ?string
    {
        return $this->three;
    }

    public function setThree( ?string $three): self
    {
        $this->three = $three;

        return $this;
    }
    public function setThreeFile(File $three = null)
    {
        $this->threeFile = $three;
        if ($three) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getThreeFile()
    {
        return $this->threeFile;
    }

    public function getFour(): ?string
    {
        return $this->four;
    }

    public function setFour( ?string $four): self
    {
        $this->four = $four;

        return $this;
    }
    public function setFourFile(File $four = null)
    {
        $this->fourFile = $four;
        if ($four) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFourFile()
    {
        return $this->fourFile;
    }
}
