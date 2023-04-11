<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixture extends Fixture
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

        //Users
        $users = [];
        for ($i = 0; $i < 10; $i++) {

            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[$i] = $user;
            $manager->persist($user);
        }

        //Ingredients
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word(1))
                ->setPrice(mt_rand(1, 100))
                ->setUser($users[mt_rand(0,count($users)-1)]);

            $ingredients[$i] = $ingredient;
            $manager->persist($ingredient);
        }

        //Recipes
        for ($i = 0; $i < 25; $i++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word(1))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(20))
                ->setPeopleRequired(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(2, 1440) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1)
                ->setUser($users[mt_rand(0,count($users)-1)])
                ->setIsPublic(mt_rand(0, 1) == 1);
            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredients($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}