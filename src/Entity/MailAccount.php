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
    private ?string $host = null;

    #[ORM\Column]
    private ?int $port = null;

    #[ORM\Column(type: Type::BUILTIN_TYPE_BOOL)]
    private ?bool $active = null;

    #[ORM\Column(type: "encrypted_string")]
    private ?string $username = null;

    #[ORM\Column(type: "encrypted_string")]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'mailAccounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group_ = null;

    public function __construct(
        private MailAccountRepository $repository
        )
    {
    }
    
    public static function create(
        string $name,
        string $host,
        int $port,
        string $username,
        string $password,
        bool $active,
        Group $group,
        
        ): self
    {
        $account = new self();
        $account->setName($name);
        $account->setHost($host);
        $account->setPort($port);
        $account->setUsername($username);
        $account->setPassword($password);
        $account->setGroup($group);
        $account->setActive($active);
        $account->save();

        return $account;
    }
    
    public function update(
        ?string $name,
        ?string $host,
        ?int $port,
        ?string $username,
        ?string $password,
        ?bool $active,
        ?Group $group,): self
    {
        if($name !== null){
            $this->setName($name);
        }
        if($host !== null){
            $this->setHost($host);
        }
        if($port !== null){
            $this->setPort($port);
        }
        if($username !== null){
            $this->setUsername($username);
        }
        if($password !== null){
            $this->setPassword($password);
        }
        if($active !== null){
            $this->setActive($active);
        }
        if($group !== null){
            $this->setGroup($group);
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

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): static
    {
        $this->port = $port;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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
    
    public function getActive(): ?bool
    {
        return $this->active;
    }
    
    public function setActive(bool $active): static
    {
        $this->active = $active;
        
        return $this;
    }
}
