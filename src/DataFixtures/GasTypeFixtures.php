<?php

namespace App\DataFixtures;

use App\Entity\GasType;
use App\Lists\GasTypeReference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GasTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (GasTypeReference::getConstantsList() as $constant) {
            $gasType = new GasType();
            $gasType
                ->setId($constant['id'])
                ->setLabel($constant['label'])
                ->setReference($constant['reference'])
            ;

            $manager->persist($gasType);
        }

        $manager->flush();
        $manager->clear();
    }
}
