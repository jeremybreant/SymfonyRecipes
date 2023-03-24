<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class IngredientFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function  __construct()
    {
       $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 50; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word(1))
                ->setPrice(mt_rand(1,100));
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
