<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;

class SalleFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        for($i = 1; $i <= 5; $i++){
            $salle = new Salle();
            $salle->setNomSalle($faker->lastName);
            $salle->setCapacite($faker->numberBetween(2,10));
            $salle->setSlug($faker->slug);
        
            $manager->persist($salle);
     
            }
        
        // for($salle = 0; $salle <=1; $salle++){}@

        $manager->flush();
    }

}
