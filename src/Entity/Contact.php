<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    public function __toString(): string
    {
        return $this->getName();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: ContactItem::class, cascade: ['persist', 'remove'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }
    
    // public static function create(string $name, string $notes): self
    // {
    //     $contact = new self();
    //     $contact->setName($name);
    //     $contact->setNotes($notes);

    //     return $contact;
    // }
    
    // public function update(?string $name, ?string $notes): self
    // {
    //     if ($name !== null) {
    //         $this->setName($name);
    //     }
    //     if ($notes !== null) {
    //         $this->setNotes($notes);
    //     }

    //     return $this;
    // }
    
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection<int, ContactItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ContactItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setContact($this);
        }

        return $this;
    }

    public function removeItem(ContactItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getContact() === $this) {
                $item->setContact(null);
            }
        }

        return $this;
    }

}
