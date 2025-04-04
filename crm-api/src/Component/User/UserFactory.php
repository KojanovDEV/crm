<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Entity\Company;
use App\Entity\EmailOwner;
use App\Entity\MediaObject;
use App\Entity\PhoneOwner;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function create(
        string $username,
        string $password,
        string $fullname,
        array $roles,
        ?string $email = null,
        ?string $phoneNumber = null,
        ?Company $workplace = null,
        ?MediaObject $profilePhoto = null
    ): User {
        $user = new User();
        $user->setUsername($username);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setFullname($fullname);
        $user->setRoles($roles);

        if ($email) {
            $emailOwner = new EmailOwner();
            $emailOwner->setEmail($email);
            $user->setEmailOwner($emailOwner);
        }

        if ($phoneNumber) {
            $phoneOwner = new PhoneOwner();
            $phoneOwner->setPhoneNumber($phoneNumber);
            $user->setPhoneOwner($phoneOwner);
        }

        if ($workplace) {
            $user->setWorkplace($workplace);
        }

        if ($profilePhoto) {
            $user->setProfilePhoto($profilePhoto);
        }

        return $user;
    }
}
