<?php

namespace App\DataFixtures;

use App\Entity\GasStationStatus;
use App\Lists\GasStationStatusReference;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GasStationStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slugify = new Slugify();

        foreach (GasStationStatusReference::getConstantsList() as $constant) {
            $gasStationStatus = new GasStationStatus();
            $gasStationStatus
                ->setLabel($constant)
                ->setReference($slugify->slugify($constant, '_'))
            ;

            $manager->persist($gasStationStatus);
        }

        $manager->flush();
        $manager->clear();
    }
}
