<?php
namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\RefreshTokenController;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;


#[ApiResource(
    shortName: 'Refresh Token',
    operations: [
        new Post(
            uriTemplate: '/refresh_token',
            controller: RefreshTokenController::class,
            name: 'refresh_token',
            denormalizationContext: ['groups' => ['refresh']],
            openapi: new OpenApiOperation(
                summary: 'Refresh token',
                description: 'Automaticaly called when access_token expires'
            ),
            read: false,
            write: false
        )
    ],
)]
final class RefreshTokenInput
{
    #[Assert\NotBlank]
    public string $refresh_token;
}
