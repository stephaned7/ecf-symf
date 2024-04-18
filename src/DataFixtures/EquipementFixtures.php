<?php

namespace App\DataFixtures;

use App\Entity\Equipement;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class EquipementFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(private SalleRepository $salleRepo){}

    public function getOrder():int{
        return 2;
    }
    public function load(ObjectManager $manager): void
    {
        $equipement = [];
        $wifi = new Equipement();
        $wifi->setNom('Wifi');
        $manager->persist($wifi);
        $equipement[] = $wifi;

        $videoprojecteur = new Equipement();
        $videoprojecteur->setNom('Vidéoprojecteur');
        $manager->persist($videoprojecteur);
        $equipement[] = $videoprojecteur;

        $tableau = new Equipement();
        $tableau->setNom('Tableau');
        $manager->persist($tableau);
        $equipement[] = $tableau;

        $prisesElec = new Equipement();
        $prisesElec->setNom('Prises électriques');
        $manager->persist($prisesElec);
        $equipement[] = $prisesElec;

        $tv = new Equipement();
        $tv->setNom('Télévision');
        $manager->persist($tv);
        $equipement[] = $tv;

        $clim = new Equipement();
        $clim->setNom('Climatisation');
        $manager->persist($clim);
        $equipement[] = $clim;

        $salles = $this->salleRepo->findAll();

        foreach($salles as $salle){
            for($i=0; $i < mt_rand(2, count($equipement)); $i++){
                $salle->addEquipement($equipement[mt_rand(2, count($equipement) -1)]);
            }
        }


        $manager->flush();
    }
}
