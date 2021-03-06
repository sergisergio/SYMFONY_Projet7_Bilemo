<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}}
 *     },
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "swagger_context"={"summary"="Permet de récupérer l'ensemble des sociétés."},
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Seul un administrateur peut accéder à ces ressources."
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "swagger_context"={"summary"="Permet de récupérer le détail d'une société."},
 *              "access_control_message"="Seul un administrateur peut accéder à cette ressource.",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Seul un administrateur peut accéder à cette ressource."
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @UniqueEntity(fields={"company"}, message="Société déjà existante")
 * @UniqueEntity(fields={"username"}, message="Username déjà pris")
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=150)
     * * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Users", mappedBy="client")
     */
    private $users;

    /**
     * @ORM\Column(type="array")
     * @Groups({"read"})
     */
    private $roles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
