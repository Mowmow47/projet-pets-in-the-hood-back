<?php

namespace App\Entity;

use App\Repository\PetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PetRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Pet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"pet_browse", "pet_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"pet_browse", "pet_read"})
     * @Groups({"user_browse", "user_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"user_browse", "user_read"})
     */
    private $id_card;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"user_browse", "user_read"})
     */
    private $tatoo;

    /**
     * @ORM\Column(type="text")
     * @Groups({"pet_browse", "pet_read"})
     * @Groups({"user_browse", "user_read"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Breed::class, inversedBy="pets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"pet_browse", "pet_read"})
     * @Groups({"user_browse", "user_read"})
     */
    private $breed;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="pets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"pet_browse", "pet_read"})
     * @Groups({"user_browse", "user_read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"pet_browse", "pet_read"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"pet_browse", "pet_read"})
     * @Groups({"user_browse", "user_read"})
     */
    private $picture;

    public function __toString()
    {
        return $this->name;
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

    public function getIdCard(): ?string
    {
        return $this->id_card;
    }

    public function setIdCard(?string $id_card): self
    {
        $this->id_card = $id_card;

        return $this;
    }

    public function getTatoo(): ?string
    {
        return $this->tatoo;
    }

    public function setTatoo(?string $tatoo): self
    {
        $this->tatoo = $tatoo;

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

    public function getBreed(): ?Breed
    {
        return $this->breed;
    }

    public function setBreed(?Breed $breed): self
    {
        $this->breed = $breed;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

   
    
}
