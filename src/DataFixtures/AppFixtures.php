<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'motdepasse')
        );
        $manager->persist($admin);

        // Créer quelques utilisateurs normaux
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password123')
            );
            $manager->persist($user);
        }

        $manager->flush();
    }
}   
