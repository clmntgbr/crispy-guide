<?php

namespace App\EntityListener\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PreEntityListener
{
    /** @var UserPasswordHasherInterface */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function prePersist(User $user): void
    {
        $user
            ->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()))
            ->eraseCredentials()
        ;
    }

    public function preUpdate(User $user): void
    {
        if (null !== $user->getPlainPassword()) {
            $user
                ->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()))
                ->eraseCredentials()
            ;
        }
    }
}