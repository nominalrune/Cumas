<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use eXorus\PhpMimeMailParser\Parser;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $sender_type = null;

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
    private ?ContactItem $contact = null;

    public function __toString() : string
    {
        return $this->getSubject();

    }

    public function __construct(
    ) {
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getSenderType() : ?int
    {
        return $this->sender_type;
    }

    public function setSenderType(int $sender_type) : static
    {
        $this->sender_type = $sender_type;

        return $this;
    }

    public function getFile() : ?string
    {
        return $this->file;
    }

    public function setFile(string $file) : static
    {
        $this->file = $file;

        return $this;
    }

    public function getMessageId() : ?string
    {
        return $this->messageId;
    }

    public function setMessageId(string $messageId) : static
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function getReferenceId() : ?string
    {
        return $this->referenceId;
    }

    public function setReferenceId(?string $referenceId) : static
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getSubject() : ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject) : static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getInquiry() : ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(?Inquiry $inquiry) : static
    {
        $this->inquiry = $inquiry;

        return $this;
    }

    public function getContact() : ?ContactItem
    {
        return $this->contact;
    }

    public function setContact(?ContactItem $contact) : static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getBody()
    {
        $filepath = getenv('ROOT_DIR') . $this->getFile();
        if (! file_exists($filepath)) {
            return '';
        }
        $file = file_get_contents($filepath);
        
        $parser = new Parser();
        $parser->setText($file);
        $mail = $parser->getMessageBody('text');
        return print_r($mail, true) . "\n";
    }
    public static function create(
        Inquiry $inquiry,
        ?ContactItem $contact,
        string $subject,
        int $sender_type,
        string $file,
        string $messageId,
        string $referenceId
    ) : self {
        $message = new self();
        $message->setInquiry($inquiry);
        $message->setContact($contact);
        $message->setSubject($subject);
        $message->setSenderType($sender_type);
        $message->setFile($file);
        $message->setMessageId($messageId);
        $message->setReferenceId($referenceId);

        return $message;
    }

    public function update(
        ?Inquiry $inquiry,
        ?ContactItem $contact,
        ?string $subject,
        ?int $sender_type,
        ?string $file,
        ?string $messageId,
        ?string $referenceId) : self
    {
        if ($inquiry !== null) {
            $this->setInquiry($inquiry);
        }
        if ($contact !== null) {
            $this->setContact($contact);
        }
        if ($subject !== null) {
            $this->setSubject($subject);
        }
        if ($sender_type !== null) {
            $this->setSenderType($sender_type);
        }
        if ($file !== null) {
            $this->setFile($file);
        }
        if ($messageId !== null) {
            $this->setMessageId($messageId);
        }
        if ($referenceId !== null) {
            $this->setReferenceId($referenceId);
        }

        return $this;
    }

}
