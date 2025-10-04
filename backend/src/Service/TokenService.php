<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TokenService
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private RefreshTokenManagerInterface $refreshTokenManager,
        private Security $security,
        private UserRepository $userRepository
    ) {}
    
    /**
     * Crée un access token et un refresh token pour un utilisateur
     */
    public function createTokens(User $user): array
    {
        $token = $this->jwtManager->create($user);

        $refreshToken = $this->refreshTokenManager->create();
        $refreshToken->setUsername($user->getUserIdentifier());
        $refreshToken->setRefreshToken(bin2hex(random_bytes(32)));
        $refreshToken->setValid(new \DateTimeImmutable('+30 days'));
        $this->refreshTokenManager->save($refreshToken);

        return [
            'token' => $token,
            'refresh_token' => $refreshToken->getRefreshToken(),
        ];
    }

    /**
     * Rafraîchit l'access token à partir du refresh token
     */
    public function refreshAccessToken(string $refreshTokenString): array
    {
        $refreshToken = $this->refreshTokenManager->get($refreshTokenString);

        if (!$refreshToken) {
            throw new UnauthorizedHttpException('', 'Refresh token invalide');
        }

        // Vérifie si le token n’est pas expiré
        // Récupère la date de validité
        $validUntil = $refreshToken->getValid();

        if ($validUntil < new \DateTimeImmutable()) {
            throw new UnauthorizedHttpException('', 'Refresh token expiré');
        }
        
        $username = $refreshToken->getUsername();

        // Récupère l’utilisateur correspondant
        $user = $this->userRepository->findByEmail($username);

        if (!$user) {
            throw new UnauthorizedHttpException('', 'Utilisateur introuvable pour ce refresh token');
        }

        // Crée un nouvel access token (on peut garder le même refresh token ou en générer un nouveau si souhaité)
        $accessToken = $this->jwtManager->create($user);

        return [
            'token' => $accessToken,
            'refresh_token' => $refreshTokenString
        ];
    }
}
