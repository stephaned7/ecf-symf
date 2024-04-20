<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Salle;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SalleFixtures extends Fixture
{
   
    public function load(ObjectManager $manager): void

    {

        $faker = Factory::create('fr_FR');

        for($i = 0; $i <= 4; $i++){
            $salle = new Salle();
            $salle->setNom($faker->lastName);
            $salle->setCapacite($faker->numberBetween(2,10));
            
            $manager->persist($salle);

            }

        $manager->flush();
    }
}
