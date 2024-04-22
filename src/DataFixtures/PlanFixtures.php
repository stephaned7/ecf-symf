<?php

namespace App\DataFixtures;

    public function load(ObjectManager $manager): void
    {
        $mensuel = new Plan();
        $mensuel->setName('Mensuel');
        $mensuel->setCreatedAt(new \DateTime());
        $mensuel->setPrice(2399);
        $mensuel->setPaymentLink('https://buy.stripe.com/test_7sIcNodK4fDfdtS9AA');
        $manager->persist($mensuel);

        $annuel = new Plan();
        $annuel->setName('Annuel');
        $annuel->setCreatedAt(new \DateTime());
        $annuel->setPrice(23990);
        $annuel->setPaymentLink('https://buy.stripe.com/test_aEU28K49ubmZblKbIJ');
        $manager->persist($annuel);
        

        $manager->flush();
    }