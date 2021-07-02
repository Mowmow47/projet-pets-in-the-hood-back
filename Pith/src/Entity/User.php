<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"pet_browse", "pet_read", "advert_browse", "advert_read", "user_browse", "user_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_browse", "user_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_browse", "user_read"}) 
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user_browse", "user_read"}) 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"user_browse", "user_read", "advert_browse", "advert_read"}) 
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"user_browse", "user_read", "advert_browse", "advert_read"}) 
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"user_browse", "user_read", "advert_browse", "advert_read"}) 
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Pet::class, mappedBy="user")
     * @Groups({"user_browse", "user_read"}) 
     */
    private $pets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBlocked;
    
    /**
     * @ORM\OneToMany(targetEntity=Advert::class, mappedBy="user")
     */
    private $adverts;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     * @Groups({"user_browse", "user_read", "advert_browse", "advert_read"}) 
     */
    private $address;

    public function __construct()
    {
        $this->pets = new ArrayCollection();
        $this->adverts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstname.' '.$this->lastname;
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    /**
     * @return Collection|Pet[]
     */
    public function getPets(): Collection
    {
        return $this->pets;
    }

    public function addPet(Pet $pet): self
    {
        if (!$this->pets->contains($pet)) {
            $this->pets[] = $pet;
            $pet->setUser($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): self
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getUser() === $this) {
                $pet->setUser(null);
            }
        }

        return $this;
    }

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    

    /**
     * @return Collection|Advert[]
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert): self
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts[] = $advert;
            $advert->setUser($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): self
    {
        if ($this->adverts->removeElement($advert)) {
            // set the owning side to null (unless already changed)
            if ($advert->getUser() === $this) {
                $advert->setUser(null);
            }
        }

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
}
