<?php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('teacher@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $names = ['Alice Dupont', 'Bob Martin', 'Chloé Bernard', 'David Leroy', 'Emma Petit'];
        foreach ($names as $name) {
            $s = new Student();
            $s->setName($name);
            $manager->persist($s);
        }

        $manager->flush();
    }
}
