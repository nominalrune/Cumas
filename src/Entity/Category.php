<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
        operations: [
            new Get(normalizationContext: ['groups' => 'categoriy:item']),
            new GetCollection(normalizationContext: ['groups' => 'categoriy:list'])
        ],
        paginationEnabled: false,
    )]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:list', 'category:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category:list', 'category:item'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['category:list', 'category:item'])]
    private ?Group $group_ = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    #[Groups(['category:list', 'category:item'])]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    #[Groups(['category:list', 'category:item'])]
    private Collection $categories;

    public function __toString(): string
    {
        return $this->getName();
    }
    
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }
        
    public static function create( string $name, Group $group, ?self $parent = null): self
    {
        $category = new self();
        $category->setName($name);
        $category->setGroup($group);
        $category->setParent($parent);

        return $category;
    }
    
    public function update( ?string $name, ?Group $group, ?self $parent = null): self
    {
        if(!is_null($name)){
            $this->setName($name);
        }
        if(!is_null($group)){
            $this->setGroup($group);
        }
        if(!is_null($parent)){
            $this->setParent($parent);
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

    public function getGroup(): ?Group
    {
        return $this->group_;
    }

    public function setGroup(?Group $group_): static
    {
        $this->group_ = $group_;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        return $this;
    }
}
