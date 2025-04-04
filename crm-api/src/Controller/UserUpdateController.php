<?php

namespace App\Controller;

use App\Entity\EmailOwner;
use App\Entity\MediaObject;
use App\Entity\PhoneOwner;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
#[Route('/api/users/update', methods: ['PUT', 'PATCH'])]
class UserUpdateController extends AbstractController
{
    public function __invoke(
        Request $request,
        Security $security,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        SerializerInterface $serializer
    ): JsonResponse {
        /** @var User $user */
        $user = $security->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Foydalanuvchi topilmadi'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return new JsonResponse(['message' => 'Noto‘g‘ri formatdagi ma’lumot'], 400);
        }

        if (isset($data['username'])) {
            $user->setUsername(trim($data['username']));
        }

        if (isset($data['fullname'])) {
            $user->setFullname(trim($data['fullname']));
        }

        if (isset($data['password'])) {
            if (strlen($data['password']) < 8) {
                return new JsonResponse(['message' => 'Parol kamida 8 ta belgidan iborat bo‘lishi kerak'], 400);
            }
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (isset($data['emailOwner']['email'])) {
            $email = trim($data['emailOwner']['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return new JsonResponse(['message' => 'Noto‘g‘ri email formati'], 400);
            }

            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['emailOwner.email' => $email]);
            if ($existingUser && $existingUser !== $user) {
                return new JsonResponse(['message' => 'Bu e-mail allaqachon mavjud'], 400);
            }

            $emailOwner = $user->getEmailOwner() ?? new EmailOwner();
            $emailOwner->setEmail($email);
            $user->setEmailOwner($emailOwner);
            $entityManager->persist($emailOwner);
        }

        if (isset($data['phoneOwner']['phoneNumber'])) {
            $phoneNumber = trim($data['phoneOwner']['phoneNumber']);
            if (!preg_match('/^\+?\d{9,15}$/', $phoneNumber)) {
                return new JsonResponse(['message' => 'Noto‘g‘ri telefon raqam formati'], 400);
            }

            $phoneOwner = $user->getPhoneOwner() ?? new PhoneOwner();
            $phoneOwner->setPhoneNumber($phoneNumber);
            $user->setPhoneOwner($phoneOwner);
            $entityManager->persist($phoneOwner);
        }

        // ✅ PROFILE PHOTO TO‘G‘RI OLISH VA TEKSHIRISH
        if (!empty($data['profilePhoto'])) {
            $mediaId = is_array($data['profilePhoto']) ? ($data['profilePhoto']['@id'] ?? null) : $data['profilePhoto'];

            if (is_string($mediaId)) {
                preg_match('/(\d+)$/', $mediaId, $matches);
                $mediaId = $matches[1] ?? null;
            }

            if ($mediaId) {
                $mediaObject = $entityManager->getRepository(MediaObject::class)->find($mediaId);
                if (!$mediaObject) {
                    return new JsonResponse(['message' => 'Profil rasmi topilmadi'], 404);
                }
                $user->setProfilePhoto($mediaObject);
            }
        }

        // ✅ FOYDALANUVCHINI SAQLASH
        $entityManager->beginTransaction();
        try {
            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->commit();

            return new JsonResponse($serializer->normalize($user, null, ['groups' => ['user:read']]));
        } catch (\Exception $e) {
            $entityManager->rollback();
            return new JsonResponse(['message' => 'Ma’lumotni saqlashda xatolik yuz berdi'], 500);
        }
    }
}
