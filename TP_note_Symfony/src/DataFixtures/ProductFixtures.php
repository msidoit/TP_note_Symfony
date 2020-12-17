<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++)
        {
            $product = new Product();

            $sentence = $faker->sentence(4);
            $name = substr($sentence, 0, strlen($sentence) - 1);

            $product->setName($name)
                    ->setDescription($faker->text(1500))
                    ->setPrice($faker->randomNumber(2))
                    ->setCreatedAt($faker->dateTimeThisYear());

            $manager->persist($product);
        }

        $manager->flush();
    }

}