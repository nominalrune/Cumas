<?php

namespace App\Entity;

use App\Repository\InquiryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InquiryRepository::class)]
class Inquiry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $contactId = null;

    #[ORM\Column(length: 511)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contact = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'inquiries')]
    private ?Group $department = null;

    #[ORM\ManyToOne(inversedBy: 'inquiries')]
    private ?User $agent = null;

    #[ORM\OneToMany(mappedBy: 'inquiry', targetEntity: Message::class)]
    private Collection $messages;

    public function __construct(
    ) {
        $this->messages = new ArrayCollection();
    }

    public static function create(
        int $contactId,
        string $title,
        string $status,
        string $notes,
        Category $category,
        Group $department,
        User $agent,
    ) : self {
        $inquiry = new self();
        $inquiry->setContactId($contactId);
        $inquiry->setTitle($title);
        $inquiry->setStatus($status);
        $inquiry->setNotes($notes);
        $inquiry->setCategory($category);
        $inquiry->setDepartment($department);
        $inquiry->setAgent($agent);

        return $inquiry;
    }

    public function update(
        ?int $contactId,
        ?string $title,
        ?string $status,
        ?string $notes,
        ?Category $category,
        ?Group $department,
        ?User $agent,
    ) : self {
        if ($contactId !== null) {
            $this->setContactId($contactId);
        }
        if ($title !== null) {
            $this->setTitle($title);
        }
        if ($status !== null) {
            $this->setStatus($status);
        }
        if ($notes !== null) {
            $this->setNotes($notes);
        }
        if ($category !== null) {
            $this->setCategory($category);
        }
        if ($department !== null) {
            $this->setDepartment($department);
        }
        if ($agent !== null) {
            $this->setAgent($agent);
        }

        return $this;
    }



    public function getId() : ?int
    {
        return $this->id;
    }

    public function getContactId() : ?int
    {
        return $this->contactId;
    }

    public function setContactId(int $contactId) : static
    {
        $this->contactId = $contactId;

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

    public function getStatus() : ?string
    {
        return $this->status;
    }

    public function setStatus(string $status) : static
    {
        $this->status = $status;

        return $this;
    }

    public function getNotes() : ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes) : static
    {
        $this->notes = $notes;

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

    public function getCategory() : ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category) : static
    {
        $this->category = $category;

        return $this;
    }

    public function getDepartment() : ?Group
    {
        return $this->department;
    }

    public function setDepartment(?Group $department) : static
    {
        $this->department = $department;

        return $this;
    }

    public function getAgent() : ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent) : static
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages() : Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message) : static
    {
        if (! $this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setInquiry($this);
        }

        return $this;
    }

    public function removeMessage(Message $message) : static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getInquiry() === $this) {
                $message->setInquiry(null);
            }
        }

        return $this;
    }

}
