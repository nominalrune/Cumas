<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'groups')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'group_', targetEntity: UserGroup::class, orphanRemoval: true)]
    private Collection $userGroups;

    #[ORM\OneToMany(mappedBy: 'group_', targetEntity: MailAccount::class)]
    private Collection $mailAccounts;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Inquiry::class)]
    private Collection $inquiries;

    #[ORM\OneToMany(mappedBy: 'group_', targetEntity: Category::class, orphanRemoval: true)]
    private Collection $categories;

    public function __construct(
    ) {
        $this->groups = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->mailAccounts = new ArrayCollection();
        $this->inquiries = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }
    public static function create(
        string $name,
        ?self $parent = null
    ) : self {
        $group = new self();
        $group->setName($name);
        $group->setParent($parent);
        $now = new \DateTimeImmutable();
        $group->setCreatedAt($now);
        $group->setUpdatedAt($now);

        return $group;
    }

    public function update(
        string $name,
        ?self $parent = null
    ) : self {
        $this->setName($name);
        $this->setParent($parent);
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(string $name) : static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt() : ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt) : static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt() : ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt) : static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getParent() : ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent) : static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getGroups() : Collection
    {
        return $this->groups;
    }

    public function addGroup(self $group) : static
    {
        if (! $this->groups->contains($group)) {
            $this->groups->add($group);
            $group->setParent($this);
        }

        return $this;
    }

    public function removeGroup(self $group) : static
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getParent() === $this) {
                $group->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserGroup>
     */
    public function getUserGroups() : Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup) : static
    {
        if (! $this->userGroups->contains($userGroup)) {
            $this->userGroups->add($userGroup);
            $userGroup->setGroup($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup) : static
    {
        if ($this->userGroups->removeElement($userGroup)) {
            // set the owning side to null (unless already changed)
            if ($userGroup->getGroup() === $this) {
                $userGroup->setGroup(null);
            }
        }

        return $this;
    }

    public function getUsers() : Collection
    {
        return $this->userGroups->map(fn (UserGroup $userGroup) => $userGroup->getUser());
    }

    public function addUser(User $user) : static
    {
        if (! $this->getUsers()->contains($user)) {
            $userGroup = UserGroup::create($user, $this);
            $this->userGroups->add($userGroup);
            $userGroup->setGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user) : static
    {
        $userGroup = $this->userGroups->filter(
            fn (UserGroup $userGroup) => $userGroup->getUser()->id === $user->id
            )->first();
        if ($this->userGroups->removeElement($userGroup)) {
            // set the owning side to null (unless already changed)
            if ($userGroup->getGroup() === $this) {
                $userGroup->setGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MailAccount>
     */
    public function getMailAccounts() : Collection
    {
        return $this->mailAccounts;
    }

    public function addMailAccount(MailAccount $mailAccount) : static
    {
        if (! $this->mailAccounts->contains($mailAccount)) {
            $this->mailAccounts->add($mailAccount);
            $mailAccount->setGroup($this);
        }

        return $this;
    }

    public function removeMailAccount(MailAccount $mailAccount) : static
    {
        if ($this->mailAccounts->removeElement($mailAccount)) {
            // set the owning side to null (unless already changed)
            if ($mailAccount->getGroup() === $this) {
                $mailAccount->setGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inquiry>
     */
    public function getInquiries() : Collection
    {
        return $this->inquiries;
    }

    public function addInquiry(Inquiry $inquiry) : static
    {
        if (! $this->inquiries->contains($inquiry)) {
            $this->inquiries->add($inquiry);
            $inquiry->setDepartment($this);
        }

        return $this;
    }

    public function removeInquiry(Inquiry $inquiry) : static
    {
        if ($this->inquiries->removeElement($inquiry)) {
            // set the owning side to null (unless already changed)
            if ($inquiry->getDepartment() === $this) {
                $inquiry->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories() : Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category) : static
    {
        if (! $this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setGroup($this);
        }

        return $this;
    }

    public function removeCategory(Category $category) : static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getGroup() === $this) {
                $category->setGroup(null);
            }
        }

        return $this;
    }
}
