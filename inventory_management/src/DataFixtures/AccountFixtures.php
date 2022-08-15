<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountFixtures extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->hasher = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager): void
    {
        // Product Admin
        $account = new Account;
        $account->setUsername("admin1");
        $account->setRoles(['ROLE_PRD_ADMIN']);
        $account->setPassword($this->hasher->hashPassword($account, "123456"));
        $manager->persist($account);

        // Account Admin
        $account = new Account;
        $account->setUsername("admin2");
        $account->setRoles(['ROLE_ACC_ADMIN']);
        $account->setPassword($this->hasher->hashPassword($account, "123456"));
        $manager->persist($account);

        // Staff
        $account = new Account;
        $account->setUsername("staff");
        $account->setRoles(['ROLE_STAFF']);
        $account->setPassword($this->hasher->hashPassword($account, "123456"));
        $manager->persist($account);

        $manager->flush();
    }
}