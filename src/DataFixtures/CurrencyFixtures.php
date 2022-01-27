<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Lists\CurrencyReference;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slugify = new Slugify();

        foreach (CurrencyReference::getConstantsList() as $constant) {
            $currency = new Currency();
            $currency
                ->setLabel($constant)
                ->setReference($slugify->slugify($constant))
            ;

            $manager->persist($currency);
        }

        $manager->flush();
        $manager->clear();
    }
}
