<?php

namespace App\Controller;

use App\Dto\RegisterInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = $request->attributes->get('data');
        if ($data instanceof RegisterInput) {
            $email = trim($data->email);
            $username = $data->username;
            $plainPassword = $data->password;
        } else {
            $payload = json_decode($request->getContent() ?: '{}', true);
            $email = is_string(($payload['email'] ?? null)) ? trim($payload['email']) : '';
            $username = is_string(($payload['username'] ?? null)) ? trim($payload['username']) : '';
            $plainPassword = (string) ($payload['password'] ?? '');
        }

        $violations = $validator->validate($email, [
            new Assert\NotBlank(),
            new Assert\Email(),
            new Assert\Length(max: 180),
        ]);

        $usernameViolations = $validator->validate($username, [
            new Assert\NotBlank(),
            new Assert\Length(max: 255),
        ]);

        $passwordViolations = $validator->validate($plainPassword, [
            new Assert\NotBlank(),
            new Assert\Length(min: 6),
        ]);

        if (\count($violations) > 0 || \count($passwordViolations) > 0 || \count($usernameViolations) > 0){
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            foreach ($usernameViolations as $violation) {
                $errors[] = $violation->getMessage();
            }
            foreach ($passwordViolations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $existing = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existing !== null) {
            return $this->json(['error' => 'Email is already registered'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $hashed = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashed);
        $user->setRoles(['ROLE_USER']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ], Response::HTTP_CREATED);
    }
}


