<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\UserController;
use App\Controller\UserCreateAction;
use App\Controller\UserCreateClientAction;
use App\Controller\UserUpdateController;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: 'users/clients',
            controller: UserController::class . '::getClients',
            name: 'userClients'
        ),
        new GetCollection(
            uriTemplate: 'user/generals',
            controller: UserController::class . '::getGenerals',
            name: 'userGenerals'
        ),
        new GetCollection(
            uriTemplate: 'users',
            controller: UserController::class . '::getUsers',
            name: 'userList'
        ),
        new Post(
            uriTemplate: 'users/create',
            controller: UserCreateAction::class,
            name: 'userCreate'
        ),
        new Post(
            uriTemplate: 'users/create-client',
            controller: UserCreateClientAction::class
        ),
        new GetCollection(
            uriTemplate: 'user/me',
            controller: UserController::class . '::getMe',
            name: 'userMe'
        ),
        new Post(
            uriTemplate: 'users/auth',
            name: 'userAuth'
        ),
        new GetCollection(),
        new Delete(
            uriTemplate: '/users/{id}/delete',
            controller: UserController::class . '::softDelete',
            name: 'userDelete'
        ),
        new Put(
            uriTemplate: '/users/update',
            controller: UserUpdateController::class,
            security: "is_granted('ROLE_USER')"
        ),
        new Patch(
            uriTemplate: '/users/update',
            controller: UserUpdateController::class,
            security: "is_granted('ROLE_USER')"
        )
    ],
    formats: ['json' => ['application/json'], 'jsonld' => ['application/ld+json']],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write', 'user:update']],
    paginationClientEnabled: true,
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(BooleanFilter::class, properties: ['deletedAt'])]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'user:update'])]
    private string $fullname;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Parol bo‘sh bo‘lishi mumkin emas.")]
    #[Assert\Length(
        min: 8,
        max: 64,
        minMessage: "Parol kamida {{ limit }} ta belgidan iborat bo‘lishi kerak.",
        maxMessage: "Parol maksimal {{ limit }} ta belgidan oshmasligi kerak."
    )]
    #[Assert\Regex(
        pattern: "/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}/",
        message: "Parol kamida bitta katta harf, bitta kichik harf, bitta raqam va bitta maxsus belgi (misol: @, #, !) bo‘lishi kerak."
    )]
    #[Groups(['user:write', 'user:update'])]
    private string $password;

    #[ORM\OneToOne(targetEntity: EmailOwner::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['user:read', 'user:write', 'user:update'])]
    private ?EmailOwner $emailOwner = null;

    #[ORM\OneToOne(targetEntity: PhoneOwner::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['user:read', 'user:write', 'user:update'])]
    private ?PhoneOwner $phoneOwner = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[Groups(['user:read', 'user:write'])]
    private ?Company $workplace = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class, cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(writable: true, writableLink: true)]
    #[Groups(['user:read', 'user:write', 'user:update'])]
    private ?MediaObject $profilePhoto = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $lastActiveAt = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    #[Groups(['user:read', 'deleted_at'])]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, updatable: false)]
    #[Groups(['user:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "json")]
    #[Groups(['user:read', 'user:write'])]
    private array $roles = [];

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user:read', 'user:write', 'user:update'])]
    private ?string $username = null;

    public function __construct()
    {
        date_default_timezone_set('Asia/Tashkent');
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmailOwner(): ?EmailOwner
    {
        return $this->emailOwner;
    }

    public function setEmailOwner(?EmailOwner $emailOwner): self
    {
        $this->emailOwner = $emailOwner;
        return $this;
    }

    public function getPhoneOwner(): ?PhoneOwner
    {
        return $this->phoneOwner;
    }

    public function setPhoneOwner(?PhoneOwner $phoneOwner): self
    {
        $this->phoneOwner = $phoneOwner;
        return $this;
    }

    public function getWorkplace(): ?Company
    {
        return $this->workplace;
    }

    public function setWorkplace(?Company $workplace): self
    {
        $this->workplace = $workplace;
        return $this;
    }

    public function getProfilePhoto(): ?MediaObject
    {
        return $this->profilePhoto;
    }

    public function setProfilePhoto(?MediaObject $profilePhoto): self
    {
        $this->profilePhoto = $profilePhoto;
        return $this;
    }

    public function getLastActiveAt(): ?\DateTimeImmutable
    {
        return $this->lastActiveAt;
    }

    public function setLastActiveAt(?\DateTimeImmutable $lastActiveAt): self
    {
        $this->lastActiveAt = $lastActiveAt;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }
}
