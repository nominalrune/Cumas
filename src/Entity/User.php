<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    // #[ORM\OneToMany(mappedBy: '`user`', targetEntity: UserGroup::class, orphanRemoval: true)]
    // private Collection $userGroups;

    // #[ORM\JoinTable(name: '`user_group`')]
    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'agent', targetEntity: Inquiry::class)]
    private Collection $inquiries;

    public function __toString(): string
    {
        return $this->getName();
    }

    public function __construct(
        )
    {
        $this->userGroups = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->inquiries = new ArrayCollection();
    }

    public static function create(
        string $name,
        string $email,
        string $password,
        ?UserGroup $userGroup = null
        ): self
    {
        $user = new self();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        if ($userGroup) {
            $user->addUserGroup($userGroup);
        }

        return $user;
    }
    
    public function update(
        ?string $name,
        ?string $email,
        ?string $password
        ): self
    {
        if($name !== null) {
            $this->setName($name);
        }
        if($email !== null) {
            $this->setEmail($email);
        }
        if($password !== null) {
            $this->setPassword($password);
        }

        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, UserGroup>
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): static
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups->add($userGroup);
            $userGroup->setUser($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): static
    {
        if ($this->userGroups->removeElement($userGroup)) {
            // set the owning side to null (unless already changed)
            if ($userGroup->getUser() === $this) {
                $userGroup->setUser(null);
            }
        }

        return $this;
    }

    /**
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): static
    {
        if (! $this->groups->contains($group)) {
            $userGroup = UserGroup::create($this,$group);
            $this->userGroups->add($userGroup);
            $group->addUserGroup($userGroup);
        }

        return $this;
    }

    public function removeGroup(Group $group): static
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->users->contains($this)) {
                $group->removeUser($this);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inquiry>
     */
    public function getInquiries(): Collection
    {
        return $this->inquiries;
    }

    public function addInquiry(Inquiry $inquiry): static
    {
        if (!$this->inquiries->contains($inquiry)) {
            $this->inquiries->add($inquiry);
            $inquiry->setAgent($this);
        }

        return $this;
    }

    public function removeInquiry(Inquiry $inquiry): static
    {
        if ($this->inquiries->removeElement($inquiry)) {
            // set the owning side to null (unless already changed)
            if ($inquiry->getAgent() === $this) {
                $inquiry->setAgent(null);
            }
        }

        return $this;
    }

}
