<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ctgr = array("VietNam", "China", "Japan", "USA");
        for ($i = 0; $i < 3; $i++) {
            $country = new Country;
            $country->setName("$ctgr[$i]")
            ->setImage("https://picsum.photos/200/250");
            $manager->persist($country);
        }
        $manager->flush();
    }
}
