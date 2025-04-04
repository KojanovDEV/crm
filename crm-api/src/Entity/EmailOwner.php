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
    normalizationContext: ['groups' => ['emailOwner:read']],
    denormalizationContext: ['groups' => ['emailOwner:write']],
)]
#[ORM\Entity]
#[ORM\UniqueConstraint(name: "unique_email", columns: ["email"])]
class EmailOwner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['emailOwner:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['emailOwner:read', 'emailOwner:write', 'user:write', 'user:read', 'company:read', 'company:write'])]
    private string $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
}