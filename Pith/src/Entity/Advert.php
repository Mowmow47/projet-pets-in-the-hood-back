<?php

namespace App\Entity;

use App\Repository\AdvertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdvertRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"advert_browse", "advert_read"})
     * @Groups({"pet_browse", "pet_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"advert_browse", "advert_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"advert_browse", "advert_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"advert_browse", "advert_read"})
     */
    private $dateOfLoss;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"advert_browse", "advert_read"})
     */
    private $dateOfDiscovery;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"advert_browse", "advert_read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"advert_read"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"advert_read"})
     * @Groups({"pet_browse", "pet_read"})
     */
    private $isActive;

    /**
     * @ORM\OneToOne(fetch = "EAGER", targetEntity=Address::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"advert_browse", "advert_read"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adverts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"advert_browse", "advert_read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Pet::class, inversedBy="adverts")
     * @Groups({"advert_browse", "advert_read"})
     */
    private $pet;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"advert_browse", "advert_read"})
     */
    private $picture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReported;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isActive = false;
        $this->isReported = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateOfLoss(): ?\DateTimeInterface
    {
        return $this->dateOfLoss;
    }

    public function setDateOfLoss(\DateTimeInterface $dateOfLoss): self
    {
        $this->dateOfLoss = $dateOfLoss;

        return $this;
    }

    public function getDateOfDiscovery(): ?\DateTimeInterface
    {
        return $this->dateOfDiscovery;
    }

    public function setDateOfDiscovery(?\DateTimeInterface $dateOfDiscovery): self
    {
        $this->dateOfDiscovery = $dateOfDiscovery;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

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

    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    public function setPet(?Pet $pet): self
    {
        $this->pet = $pet;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
  
    public function getIsReported(): ?bool
    {
        return $this->isReported;
    }
    
    public function setIsReported(bool $isReported): self
    {
        $this->isReported = $isReported;
        
        return $this;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}
