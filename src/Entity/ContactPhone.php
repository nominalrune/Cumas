<?php

namespace App\Entity;

use App\Repository\ContactPhoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactPhoneRepository::class)]
class ContactPhone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $contactId = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'phones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contact = null;

    public function __toString(): string
    {
        return $this->getPhone();
    }
    public function __construct(
        )
    {
    }
    
    public static function create(
        int $contactId,
        string $phone,
        ?string $notes = null
        ): self
    {
        $contact = new self();
        $contact->setContactId($contactId);
        $contact->setPhone($phone);
        $contact->setNotes($notes);

        return $contact;
    }
    
    public function update(
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

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
