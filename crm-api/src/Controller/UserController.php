<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Faol foydalanuvchilar ro‘yxatini olish (pagination qo‘shilgan)
     */
    #[Route('/api/users', name: 'user_list', methods: ['GET'])]
    public function getUsers(Request $request): JsonResponse
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = max(1, (int)$request->query->get('itemsPerPage', 10));

        $paginator = $this->userRepository->findActiveUsersPaginated($page, $limit);
        $users = iterator_to_array($paginator->getIterator());

        return $this->json([
            'data' => $users,
            'total' => count($paginator),
            'page' => $page,
            'itemsPerPage' => $limit,
        ], 200, [], ['groups' => 'user:read']);
    }

    /**
     * Foydalanuvchini yumshoq o‘chirish (soft delete)
     */
    #[Route('/api/users/{id}/delete', name: 'user_delete', methods: ['DELETE'])]
    public function softDelete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $user->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();

        return new JsonResponse(['message' => 'User deleted successfully']);
    }

    /**
     * Mijozlarni olish
     */
    #[Route('/api/users/clients', name: 'user_clients', methods: ['GET'])]
    public function getClients(Request $request): JsonResponse
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = max(1, (int)$request->query->get('itemsPerPage', 10));

        $paginator = $this->userRepository->findUsersByRolePaginated(RoleEnum::ROLE_MIJOZ->value, $page, $limit);
        $clients = iterator_to_array($paginator->getIterator());

        return $this->json([
            'data' => $clients,
            'total' => count($paginator),
            'page' => $page,
            'itemsPerPage' => $limit,
        ], 200, [], ['groups' => 'user:read']);
    }

    /**
     * Oddiy foydalanuvchilarni olish
     */
    #[Route('/api/users/generals', name: 'user_generals', methods: ['GET'])]
    public function getGenerals(Request $request): JsonResponse
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = max(1, (int)$request->query->get('itemsPerPage', 10));

        $paginator = $this->userRepository->findUsersByRolePaginated(RoleEnum::ROLE_USER->value, $page, $limit);
        $users = iterator_to_array($paginator->getIterator());

        return $this->json([
            'data' => $users,
            'total' => count($paginator),
            'page' => $page,
            'itemsPerPage' => $limit,
        ], 200, [], ['groups' => 'user:read']);
    }

    /**
     * Joriy foydalanuvchini olish
     */
    #[Route('/api/user/me', name: 'user_me', methods: ['GET'])]
    public function getMe(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }
}
