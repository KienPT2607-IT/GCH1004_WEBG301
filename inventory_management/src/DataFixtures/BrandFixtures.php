<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 6; $i++) {
            $brand = new Brand;
            $brand->setName("Brand $i")
                ->setImage("https://picsum.photos/200/250");
            $manager->persist($brand);
        }
        $manager->flush();
    }
}