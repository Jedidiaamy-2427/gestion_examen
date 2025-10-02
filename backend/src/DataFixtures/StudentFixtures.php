<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = [
            'Alice Dupont', 'Bob Martin', 'Chloé Bernard', 'David Leroy', 'Emma Petit',
            'Farid Benali', 'Gaëlle Robert', 'Hugo Fontaine', 'Inès Laurent', 'Jules Moreau',
            'Kenza Haddad', 'Léo Garnier', 'Maya Rousseau', 'Noah Caron', 'Omar Nadir',
            'Pauline Mercier', 'Quentin Blanc', 'Rania Fleury', 'Samir Chevalier', 'Théo Roussel'
        ];

        foreach ($names as $name) {
            $student = new Student();
            $student->setName($name);
            $manager->persist($student);
        }

        $manager->flush();
    }
}


