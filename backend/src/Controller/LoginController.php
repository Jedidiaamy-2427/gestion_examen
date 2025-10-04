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
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;

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
            return $this->json([
                'error' => 'Email et le mot de passe sont requis',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_OK);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user === null || !$passwordHasher->isPasswordValid($user, $plainPassword)) {
            return $this->json([
                'error' => 'Email ou mot de passe invalides',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_OK);
        }

        $tokens = $this->tokenService->createTokens($user);
        $token = $tokens['token'];
        $refreshToken = $tokens['refresh_token'];

        return $this->json([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'token' => $token,
            'refresh_token' => $refreshToken
        ], Response::HTTP_OK);
    }


    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(Request $request, RefreshTokenManagerInterface $refreshTokenManager,): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true);
        $refreshToken = $payload['refresh_token'] ?? null;

        if (!$refreshToken) {
            return $this->json(['error' => 'Refresh token is required', 'status' => Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
        }

        $token = $refreshTokenManager->get($refreshToken);
        if (!$token) {
            return $this->json(['error' => 'Invalid refresh token', 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        // Supprime le refresh token (invalidation)
        $refreshTokenManager->delete($token);

        return $this->json([
            'message' => 'Logout successful. Token invalidated.'
        ], Response::HTTP_OK);
    }

}


