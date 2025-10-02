<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository as BundleRefreshTokenRepository;

#[ORM\Entity(repositoryClass: BundleRefreshTokenRepository::class)]
#[ORM\Table(name: 'refresh_tokens')]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(name: 'refresh_token', type: 'string', length: 128, unique: true, nullable: false)]
    protected $refreshToken;

    #[ORM\Column(name: 'username', type: 'string', length: 255, nullable: false)]
    protected $username;

    #[ORM\Column(name: 'valid', type: 'datetime', nullable: false)]
    protected $valid;
}


