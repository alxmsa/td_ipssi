<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        // Admin user fixture
        $admin = new User();
        $admin->setEmail('moussapro.dp@gmail.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'qwerty'));
        $manager->persist($admin);
        $this->addReference('admin-user', $admin);

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail("user$i@gmail.com");
            $user->setFirstName("User$i");
            $user->setLastName('Test');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'qwerty'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
