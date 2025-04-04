<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\PhoneOwnerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new Delete(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['phoneOwner:read']],
    denormalizationContext: ['groups' => ['phoneOwner:write']],
)]
#[ORM\Entity(repositoryClass: PhoneOwnerRepository::class)]
#[ORM\Table(name: "phone_owner")]
class PhoneOwner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['phoneOwner:read'])]
    private int $id;

    #[ORM\Column(type: "string", length: 20, unique: true)]
    #[Groups(['phoneOwner:read', 'phoneOwner:write', 'user:write', 'user:read', 'company:write', 'company:read'])]
    private string $phoneNumber;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }
}

