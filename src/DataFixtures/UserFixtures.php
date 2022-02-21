<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\DotEnv;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /** @var DotEnv */
    private $dotEnv;

    public function __construct(DotEnv $dotEnv)
    {
        $this->dotEnv = $dotEnv;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail($this->dotEnv->findByParameter('ROOT_USER_EMAIL'))
            ->setPlainPassword('string')
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($user);
        $manager->flush();
        $manager->clear();
    }
}
