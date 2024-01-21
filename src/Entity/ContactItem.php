<?php

namespace App\Entity;

use App\Repository\ContactItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactItemRepository::class)]
class ContactItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $contactId = null;

    #[ORM\Column(length: 511, nullable: true)]
    private ?string $title = null;
    
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'items', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contact = null;

    public function __toString() : string
    {
        return "{$this->getContact()->getName()}<{$this->getValue()}>";
    }

    public function __construct()
    {
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getType() : ?string
    {
        return $this->type;
    }

    public function setType(string $type) : static
    {
        $this->type = $type;

        return $this;
    }

    public function getValue() : ?string
    {
        return $this->value;
    }

    public function setValue(string $value) : static
    {
        $this->value = $value;

        return $this;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function setTitle(string $title) : static
    {
        $this->title = $title;

        return $this;
    }

    public function getContact() : ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact) : static
    {
        $this->contact = $contact;

        return $this;
    }

    public static function create(
        ContactItemRepository $repository,
        int $contactId,
        string $value,
        ?string $title = null,
    ) : self {
        $contact = new self();
        $contact->setContactId($contactId);
        $contact->setValue($value);
        $contact->setTitle($title);

        return $contact;
    }

    public function update(
        ContactItemRepository $repository,
        string $title) : self
    {
        $this->setTitle($title);

        return $this;
    }
}
