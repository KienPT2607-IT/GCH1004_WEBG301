<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ctgr = array("Shirt", "Dress", "Hoodies", "T-Shirt");
        for ($i = 0; $i < 3; $i++) {
            $category = new Category;
            $category->setName("$ctgr[$i]")
                ->setImage("https://picsum.photos/200/250");
            $manager->persist($category);
        }
        $manager->flush();
    }
}