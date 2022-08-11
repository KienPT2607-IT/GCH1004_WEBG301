<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 15; $i++) {
            $product = new Product;
            $product->setName("Product $i")
                ->setImage("https://picsum.photos/200/250")
                ->setPrice((float)rand(10, 30))
                ->setRemain(rand(0, 10));
            $manager->persist($product);
        }
        $manager->flush();
    }
}