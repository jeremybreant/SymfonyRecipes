<?php
declare(strict_types=1);

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Mark;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Category;
use App\Entity\RecipeIngredient;
use App\DataFixtures\UserFixture;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\DataFixtures\IngredientFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Console\Output\ConsoleOutput;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixture extends Fixture implements DependentFixtureInterface
{

    public const RECIPE = 'recipe';
    public const RECIPE_COUNT = 5000;

    /**
     * @return list<class-string<<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            CategoryFixture::class
        ];
    }

    /**
     * @var Generator
     */
    private Generator $faker;

    public UserRepository $userRepository;
    public CategoryRepository $categoryRepository;

    public function __construct(UserRepository $userRepository, CategoryRepository $categoryRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $output = new ConsoleOutput();

        //Recipes
        $output->writeln('RecipeFixture > Start reached');

        $priceConst = Recipe::getAvailablePrices();
        $difficultyConst = Recipe::getAvailableDifficulties();
        $quantityTypeConst = Recipe::getAvailableQuantityType();
        $statusConst = Recipe::getAvailableStatus();
        $count = 0;
        for ($i = 1; $i <= self::RECIPE_COUNT; $i++) {
            $count++;

            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                ->setPrice($priceConst[array_rand($priceConst)])
                ->setDifficulty($difficultyConst[array_rand($difficultyConst)])
                ->setDescription($this->faker->text(200))
                ->setFoodQuantity(mt_rand(1, 15))
                ->setFoodQuantityType($quantityTypeConst[array_rand($quantityTypeConst)])
                ->setPreparationTime(mt_rand(2, 600))
                ->setCookingTime(mt_rand(0, 1) == 1 ? mt_rand(0, 1440) : null)
                ->setUser($this->getReference(UserFixture::USER . mt_rand(1, UserFixture::USER_COUNT)))
                ->setIsPublic(mt_rand(0, 1) == 1)
                ->setStatus($statusConst[array_rand($statusConst)]);

            /** @var Category */
            $selectedCat = $this->getReference(CategoryFixture::MAIN_CATEGORY . mt_rand(1, CategoryFixture::MAIN_CATEGORY_COUNT));
            $recipeCategories = array();
            array_push($recipeCategories, $selectedCat);
            if (null != $selectedCat->getParentCategory()) {
                $parentCats = $selectedCat->getParentCatRecurcive();
                foreach ($parentCats as $parentCategory) {
                    array_push($recipeCategories, $parentCategory);
                }
            }

            //recipe ingredient
            $ingredientNumber = mt_rand(1,4);
            $ingredientAdded = 0;
            $ingredients = [];

            $unitConst = RecipeIngredient::getAvailableUnits();
            while ($ingredientAdded !== $ingredientNumber) {
                //get reandom ingredient
                $selectedIngredient = $this->getReference(IngredientFixture::INGREDIENT.mt_rand(1, IngredientFixture::INGREDIENT_COUNT));
                while(in_array($selectedIngredient,$ingredients))
                {
                    $selectedIngredient = $this->getReference(IngredientFixture::INGREDIENT.mt_rand(1, IngredientFixture::INGREDIENT_COUNT));  
                }
                $ingredients[$ingredientAdded] = $selectedIngredient;
                $recipeIngredient = new RecipeIngredient();
                $recipeIngredient->setRecipe($recipe)
                    ->setIngredient($selectedIngredient)
                    ->setQuantity(mt_rand(1, 5))
                    ->setUnitType($unitConst[array_rand($unitConst)]);
                $manager->persist($recipeIngredient);
                $ingredientAdded++;

            }

            //mark 
            $nbrOfMark = mt_rand(1,6);
            $markAdded = 0;
            $users = [];

            while ($markAdded !== $nbrOfMark) {
                //get reandom user
                $selectedUser = $this->getReference(UserFixture::USER.mt_rand(1, UserFixture::USER_COUNT));
                while(in_array($selectedUser,$users))
                {
                    $selectedUser = $this->getReference(UserFixture::USER.mt_rand(1, UserFixture::USER_COUNT));
                }
                $users[$markAdded] = $selectedUser;
                $mark = new Mark();
                $mark->setUser($selectedUser)
                    ->setMark(mt_rand(1, 5))
                    ->setComment($this->faker->text(200))
                    ->setRecipe($recipe);

                $manager->persist($mark);
                $markAdded++;
            }

            foreach ($recipeCategories as $recipeCategory) {
                $recipe->addCategory($recipeCategory);
            }
            $manager->persist($recipe);
            //$this->addReference(self::RECIPE . $i, $recipe);

            if ($count % 500 === 0) {
                $output->writeln('Flushing !' . $count);
                $manager->flush();
                $manager->clear();
            }
        }
        $output->writeln('Final Flushing !');
        $manager->flush();
        $manager->clear();

        $output->writeln('RecipeFixture > End reached');
    }
}