<?php

namespace App\Entity;

use App\Repository\ContactEmailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactEmailRepository::class)]
class ContactEmail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $contactId = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'emails',cascade:['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contact = null;
    
    public function __toString(): string
    {
        return $this->getEmail();
    }
    
    public function __construct()
    {
    }
    
    public static function create(
        ContactEmailRepository $repository,
        int $contactId,
        string $email,
        ?string $notes = null,
        ): self
    {
        $contact = new self();
        $contact->setContactId($contactId);
        $contact->setEmail($email);
        $contact->setNotes($notes);

        return $contact;
    }
    
    public function update(
        ContactEmailRepository $repository,
        string $notes): self
    {
        $this->setNotes($notes);

        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactId(): ?int
    {
        return $this->contactId;
    }

    public function setContactId(int $contactId): static
    {
        $this->contactId = $contactId;

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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }
}
