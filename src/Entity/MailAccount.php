<?php

namespace App\Entity;

use App\Repository\MailAccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Service\String\Crypt;

#[ORM\Entity(repositoryClass: MailAccountRepository::class)]
class MailAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "encrypted_string")]
    private ?string $popServer = null;

    #[ORM\Column]
    private ?int $popPort = null;

    #[ORM\Column(type: "encrypted_string")]
    private ?string $smtpServer = null;

    #[ORM\Column]
    private ?int $smtpPort = null;
    
    #[ORM\Column(type: "encrypted_string")]
    private ?string $username = null;

    #[ORM\Column(type: "encrypted_string")]
    private ?string $password = null;
    
    #[ORM\ManyToOne(inversedBy: 'mailAccounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group_ = null;
    
        #[ORM\Column(type: Types::BOOLEAN)]
        private ?bool $active = null;
        
        
    #[ORM\Column]
    private ?\DateTimeImmutable $lastCheckedAt = null;
    
    public function __toString(): string
    {
        return $this->getName();
    }
    public function __construct(
    ) {
    }

    // public static function create(
    //     string $name,
    //     string $host,
    //     int $port,
    //     string $username,
    //     string $password,
    //     bool $active,
    //     Group $group,

    // ) : self {
    //     $account = new self();
    //     $account->setName($name);
    //     $account->setHost($host);
    //     $account->setPort($port);
    //     $account->setUsername($username);
    //     $account->setPassword($password);
    //     $account->setGroup($group);
    //     $account->setActive($active);

    //     return $account;
    // }

    // public function update(
    //     ?string $name,
    //     ?string $host,
    //     ?int $port,
    //     ?string $username,
    //     ?string $password,
    //     ?bool $active,
    //     ?Group $group, ) : self
    // {
    //     if ($name !== null) {
    //         $this->setName($name);
    //     }
    //     if ($host !== null) {
    //         $this->setHost($host);
    //     }
    //     if ($port !== null) {
    //         $this->setPort($port);
    //     }
    //     if ($username !== null) {
    //         $this->setUsername($username);
    //     }
    //     if ($password !== null) {
    //         $this->setPassword($password);
    //     }
    //     if ($active !== null) {
    //         $this->setActive($active);
    //     }
    //     if ($group !== null) {
    //         $this->setGroup($group);
    //     }

    //     return $this;
    // }

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

    public function getPOPServer() : ?string
    {
        return $this->popServer;
    }

    public function setPOPServer(string $server) : static
    {
        $this->popServer = $server;

        return $this;
    }

    public function getPOPPort() : ?int
    {
        return $this->popPort;
    }

    public function setPOPPort(int $port) : static
    {
        $this->popPort = $port;

        return $this;
    }

    public function getSMTPServer() : ?string
    {
        return $this->smtpServer;
    }
    
    public function setSMTPServer(string $server) : static
    {
        $this->smtpServer = $server;

        return $this;
    }
    
    public function getSMTPPort() : ?int
    {
        return $this->smtpPort;
    }
    
    public function setSMTPPort(int $port) : static
    {
        $this->smtpPort = $port;

        return $this;
    }

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function setUsername(string $username) : static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword() : ?string
    {
        return $this->password;
    }

    public function setPassword(string $password) : static
    {
        $this->password = $password;

        return $this;
    }

    public function getGroup() : ?Group
    {
        return $this->group_;
    }

    public function setGroup(?Group $group_) : static
    {
        $this->group_ = $group_;

        return $this;
    }

    public function getActive() : ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active) : static
    {
        $this->active = $active;

        return $this;
    }
    
    public function getLastCheckedAt(){
        return $this->lastCheckedAt;
    }
    
    public function setLastCheckedAt(\DateTimeImmutable $datetime){
        $this->lastCheckedAt = $datetime;
    }
}
