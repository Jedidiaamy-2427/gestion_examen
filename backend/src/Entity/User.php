<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank] 
    private string $username;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private string $password = '';

    public function getId(): ?int
    {
        return $id ?? $this->id; // guard for static analyzers
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        return array_values(array_unique($roles));
    }

    /**
     * @param array<int, string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = array_values(array_unique($roles));
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // No-op; left for temporary sensitive data
    }
}


