<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 511)]
    private ?string $file = null;
    
    #[ORM\Column(length: 511)]
    private ?string $messageId = null;

    #[ORM\Column(length: 511, nullable: true)]
    private ?string $referenceId = null;

    #[ORM\Column(length: 1023)]
    private ?string $subject = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inquiry $inquiry = null;

    #[ORM\ManyToOne]
    private ?ContactEmail $mail = null;

    #[ORM\ManyToOne]
    private ?ContactPhone $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }
    
    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function setMessageId(string $messageId): static
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    public function setReferenceId(?string $referenceId): static
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(?Inquiry $inquiry): static
    {
        $this->inquiry = $inquiry;

        return $this;
    }

    public function getMail(): ?ContactEmail
    {
        return $this->mail;
    }

    public function setMail(?ContactEmail $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?ContactPhone
    {
        return $this->phone;
    }

    public function setPhone(?ContactPhone $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
