<?php

namespace App\DataFixtures;

use App\Entity\Equipements;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;

class EquipementsFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        for($i = 1; $i <= 5; $i++){
            $equipements = new Equipements();
            $equipements->setWifi($faker->boolean);
            $equipements->setProjecteur($faker->boolean());
            $equipements->setTableau($faker->boolean());
            $equipements->setPrisesElectriques($faker->boolean());
            $equipements->setTelevision($faker->boolean());
            $equipements->setClimatisation($faker->boolean());
           
        
            $manager->persist($equipements);
     
            }
        
        // for($salle = 0; $salle <=1; $salle++){}@

        $manager->flush();
    }

}