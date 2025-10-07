<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Property;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $user = new User();
            $user->setFirstname("Utilisateur #" . $i);
            $user->setLastname("Dupont");
            $user->setEmail("baptiste.dupont@example.com");
            $user->setPhone("0601020304");
            $user->setCity("Paris");
            $user->setAddress("10 rue de la Paix");
            $user->setPostalCode(75002);
            $user->setPassword('coucou');
        
            $manager->persist($user);
        }

        $property = new Property();
        $property->setTitle("Propriété à Paris");
        $property->setDescription("Description de la propriété");
        $property->setPricePerNight(100);
        $property->setAddress("10 rue de la Paix");
        $property->setCity("Paris");
        $property->setHost($user);

        $manager->persist($property);

        $manager->flush();
    }
}
