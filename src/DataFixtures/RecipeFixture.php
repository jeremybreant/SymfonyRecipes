<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class RecipeFixture extends Fixture
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
            $recipe = new Recipe();
            $recipe->setName($this->faker->word(1))
                ->setPrice(mt_rand(1,1000))
                ->setDifficulty(mt_rand(1,5))
                ->setDescription($this->faker->word(20))
                ->setPeopleRequired(mt_rand(1,50))
                ->setTime(mt_rand(2,1440));

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}