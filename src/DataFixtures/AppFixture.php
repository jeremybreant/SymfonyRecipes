<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredient;
use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
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

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        //Users
        $admin = new User();
        $admin->setFullName('Administrateur de SymRecipe')
            ->setPseudo(null)
            ->setEmail('admin@symrecipe.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $users[] = $admin;
        $manager->persist($admin);

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
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $ingredients[$i] = $ingredient;
            $manager->persist($ingredient);
        }

        //Recipes
        $recipes = [];
        for ($i = 0; $i < 25; $i++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word(1))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(100 * 1, 100 * 1000) / 100 : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(200))
                ->setPeopleRequired(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setPreparationTime(mt_rand(0, 1) == 1 ? mt_rand(2, 1440) : null)
                ->setCookingTime(mt_rand(0, 1) == 1 ? mt_rand(0, 1440) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1)
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ->setIsPublic(mt_rand(0, 1) == 1);

            $recipes[$i] = $recipe;
            $manager->persist($recipe);
        }

        //RecipeIngredient
        $unitConst = RecipeIngredient::getAvailableUnits();
        foreach ($recipes as $recipe) {
            foreach ($ingredients as $ingredient) {
                // 1 out of 10
                if (mt_rand(0, 9) === 0) {
                    $recipeIngredient = new RecipeIngredient();
                    $recipeIngredient->setRecipe($recipe)
                        ->setIngredient($ingredient)
                        ->setQuantity(mt_rand(1, 5))
                        ->setUnitType($unitConst[array_rand($unitConst)]);
                    $manager->persist($recipeIngredient);
                }
            }
        }


        //Marks
        foreach ($users as $user) {
            foreach ($recipes as $recipe) {
                if (mt_rand(0, 1)) {
                    $mark = new Mark();
                    $mark->setUser($user)
                        ->setMark(mt_rand(0, 5))
                        ->setRecipe($recipe);

                    $manager->persist($mark);
                }
            }
        }

        //Contact
        for ($i = 0; $i < 5; $i++) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setSubject('Demande n°' . ($i + 1))
                ->setMessage($this->faker->text());
            $manager->persist($contact);
        }

        $manager->flush();
    }
}