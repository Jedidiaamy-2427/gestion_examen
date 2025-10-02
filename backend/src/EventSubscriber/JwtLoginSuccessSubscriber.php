<?php

namespace App\EventSubscriber;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtLoginSuccessSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly RefreshTokenManagerInterface $refreshTokenManager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $refreshToken = $this->refreshTokenManager->create();
        $refreshToken->setUsername($user->getUserIdentifier());
        $refreshToken->setRefreshToken(bin2hex(random_bytes(32)));
        $refreshToken->setValid((new \DateTimeImmutable('+30 days')));
        $this->refreshTokenManager->save($refreshToken);

        $data['refresh_token'] = $refreshToken->getRefreshToken();
        $event->setData($data);
    }
}


