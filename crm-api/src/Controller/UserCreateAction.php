<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserCreateAction extends AbstractController
{
    public function __construct(
        private UserFactory $userFactory,
        private UserManager $userManager,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(User $data): User
    {
        $this->entityManager->beginTransaction();

        try {
            $user = $this->userFactory->create(
                $data->getUsername(),
                $data->getPassword(),
                $data->getFullname(),
                ['ROLE_USER'],
                $data->getEmailOwner()?->getEmail(),
                $data->getPhoneOwner()?->getPhoneNumber(),
                $data->getWorkplace(),
                $data->getProfilePhoto()
            );

            $this->userManager->save($user, isNeedFlush: true);

            $this->entityManager->commit();
            return $user;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw new BadRequestException('Foydalanuvchini yaratish muvaffaqiyatsiz bo‘ldi: ' . $e->getMessage());
        }
    }
}
