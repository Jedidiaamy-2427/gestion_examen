<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Service\TokenService;

class LoginController extends AbstractController
{   
    public function __construct(private TokenService $tokenService) {}

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $payload = json_decode($request->getContent() ?: '{}', true);
        $email = is_string($payload['email'] ?? null) ? trim($payload['email']) : '';
        $plainPassword = (string) ($payload['password'] ?? '');

        if ($email === '' || $plainPassword === '') {
            return $this->json(['error' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user === null || !$passwordHasher->isPasswordValid($user, $plainPassword)) {
            return $this->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $tokens = $this->tokenService->createTokens($user);
        $token = $tokens['token'];
        $refreshToken = $tokens['refresh_token'];

        return $this->json([
            'username' => $user->getUsername(),
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'token' => $token,
            'refresh_token' => $refreshToken
        ], Response::HTTP_OK);
    }
}


