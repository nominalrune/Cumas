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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: ContactEmail::class)]
    private Collection $emails;

    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: ContactPhone::class)]
    private Collection $phones;

    public function __construct(private ContactRepository $repository)
    {
        $this->emails = new ArrayCollection();
        $this->phones = new ArrayCollection();
    }
    
    public static function create(string $name, string $notes): self
    {
        $contact = new self();
        $contact->setName($name);
        $contact->setNotes($notes);
        $contact->save();

        return $contact;
    }
    
    public function update(?string $name, ?string $notes): self
    {
        if ($name !== null) {
            $this->setName($name);
        }
        if ($notes !== null) {
            $this->setNotes($notes);
        }
        $this->save();

        return $this;
    }
    
    private function save(): void
    {
        $this->repository->save($this);
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
     * @return Collection<int, ContactEmail>
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(ContactEmail $email): static
    {
        if (!$this->emails->contains($email)) {
            $this->emails->add($email);
            $email->setContact($this);
        }

        return $this;
    }

    public function removeEmail(ContactEmail $email): static
    {
        if ($this->emails->removeElement($email)) {
            // set the owning side to null (unless already changed)
            if ($email->getContact() === $this) {
                $email->setContact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContactPhone>
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(ContactPhone $phone): static
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setContact($this);
        }

        return $this;
    }

    public function removePhone(ContactPhone $phone): static
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getContact() === $this) {
                $phone->setContact(null);
            }
        }

        return $this;
    }
}
