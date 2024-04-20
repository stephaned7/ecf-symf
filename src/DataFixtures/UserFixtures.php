<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Admin');
        $admin->setBirthdate(new \DateTime('1980-01-01'));
        $admin->setAddress('1 rue de laa Paix');
        $admin->setZipcode('75000');
        $admin->setCity('Paris');
        $admin->setPhoneNum('0123456789');
        $admin->setPassword(
            $this->passwordHasher->hashPassword(
                $admin,
                'admin'
                )
            );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $bob = new User();
        $bob->setEmail('bob@gmail.com');
        $bob->setFirstname('Bob');
        $bob->setLastname('Bobinson');
        $bob->setBirthdate(new \DateTime('1997-04-07'));
        $bob->setAddress('2 rue de la Paix');
        $bob->setZipcode('75000');
        $bob->setCity('Paris');
        $bob->setPhoneNum('0123456789');
        $bob->setPassword(
            $this->passwordHasher->hashPassword(
                $bob,
                'bob'
            )
        );
        $bob->setRoles(['ROLE_USER']);
        $manager->persist($bob);

        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setEmail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setBirthdate($faker->dateTimeBetween('-60 years', '-18 years'));
            $user->setAddress($faker->streetAddress);
            $user->setZipcode(str_replace(' ', '', $faker->postcode));
            $user->setCity($faker->city);
            $user->setPhoneNum($faker->phoneNumber);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'password'
                )
            );
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);


        }


        $manager->flush();
    }
}
