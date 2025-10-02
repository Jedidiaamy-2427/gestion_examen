<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\RegisterController;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Register',
    operations: [
        new Post(
            uriTemplate: '/api/register',
            controller: RegisterController::class,
            name: 'api_register',
            openapi: new OpenApiOperation(
                summary: 'Create a new user account',
                description: 'Registers a user and returns minimal info'
            ),
            read: false,
            deserialize: true,
            validate: true,
            output: false,
        ),
    ],
    paginationEnabled: false,
)]
class RegisterInput
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password = '';

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $username;
}


