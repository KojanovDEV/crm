<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findActiveUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('role', '%' . $role . '%')
            ->getQuery()
            ->getResult();
    }
    public function findWithWorkplace(int $userId): ?User
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.workplace', 'w')
            ->addSelect('w')
            ->leftJoin('u.profilePhoto', 'p') // profilePhoto bilan bog‘lash
            ->addSelect('p') // Uni tanlash
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findActiveUsersPaginated(int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.deletedAt IS NULL') // O‘chirilmagan foydalanuvchilar
            ->orderBy('u.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($query);
    }
    public function findUsersByRolePaginated(string $role, int $page, int $limit): \Doctrine\ORM\Tools\Pagination\Paginator
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('role', '%' . $role . '%')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new \Doctrine\ORM\Tools\Pagination\Paginator($query);
    }
}
