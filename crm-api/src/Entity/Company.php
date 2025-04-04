<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new Delete(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['company:read']],
    denormalizationContext: ['groups' => ['company:write']],
    paginationClientEnabled: true,
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ORM\Entity]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['company:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['company:read', 'company:write', 'user:read'])]
    private string $companyName;

    #[ORM\Column(length: 255)]
    #[Groups(['company:read', 'company:write'])]
    private string $address;

    #[ORM\OneToOne(targetEntity: EmailOwner::class, cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: true, unique: true)]
    #[Groups(['company:read', 'company:write', 'user:read'])]
    private ?EmailOwner $email = null;

    #[ORM\OneToOne(targetEntity: PhoneOwner::class, cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: true, unique: true)]
    #[Groups(['company:read', 'company:write', 'user:read'])]
    private ?PhoneOwner $phoneNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getEmail(): ?EmailOwner
    {
        return $this->email;
    }

    public function setEmail(?EmailOwner $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhoneNumber(): ?PhoneOwner
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?PhoneOwner $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

}
