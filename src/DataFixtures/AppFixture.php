<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredient;
use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use Symfony\Component\Console\Output\ConsoleOutput;
use function PHPUnit\Framework\isNull;

class AppFixture extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public CategoryRepository $catergoryRepository;

    public function __construct(CategoryRepository $catergoryRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->catergoryRepository = $catergoryRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $output = new ConsoleOutput();

        $categoryFixtures = new CategoryFixture();
        $categoryFixtures->load($manager);
        $categories = $this->catergoryRepository->findAll();

        //Users
        $output->writeln('Users');
        $admin = new User();
        $admin->setFullName('Administrateur de SymRecipe')
            ->setPseudo(null)
            ->setEmail('admin@symrecipe.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $users[] = $admin;
        $manager->persist($admin);

        for ($i = 0; $i < 75; $i++) {

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
        $output->writeln('Ingredients');
        $ingredients = [];
        for ($i = 0; $i < 300; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $ingredients[$i] = $ingredient;
            $manager->persist($ingredient);
        }

        //Recipes
        $output->writeln('Recipes');
        $priceConst = Recipe::getAvailablePrices();
        $difficultyConst = Recipe::getAvailableDifficulties();
        $quantityTypeConst = Recipe::getAvailableQuantityType();
        $statusConst = Recipe::getAvailableStatus();
        $recipes = [];
        for ($i = 0; $i < 200; $i++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                ->setPrice($priceConst[array_rand($priceConst)])
                ->setDifficulty($difficultyConst[array_rand($difficultyConst)])
                ->setDescription($this->faker->text(200))
                ->setFoodQuantity(mt_rand(1, 15))
                ->setFoodQuantityType($quantityTypeConst[array_rand($quantityTypeConst)])
                ->setPreparationTime(mt_rand(2, 600))
                ->setCookingTime(mt_rand(0, 1) == 1 ? mt_rand(0, 1440) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1)
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ->setIsPublic(mt_rand(0, 1) == 1)
                ->setStatus($statusConst[array_rand($statusConst)]);

            /** @var Category */
            $selectedCat = $categories[rand(0,count($categories)-1)];
            $recipeCategories = array();
            array_push($recipeCategories, $selectedCat);
            $output->writeln('Selected Cat : '.$selectedCat->getName());
            if(!empty($selectedCat->getParentCategories()->toArray())){
                $output->writeln('Going deeper');
                $parentCats = $selectedCat->getParentCatRecurcive();
                $output->writeln('Going back');
                foreach($parentCats as $parentCategory){
                    $output->writeln('Parent Category : '.$parentCategory->getName());
                    array_push($recipeCategories, $parentCategory);
                }
            }      

            foreach($recipeCategories as $recipeCategory){
                $output->writeln('Adding category : '.$recipeCategory->getName());
                $recipe->addCategory($recipeCategory);
            }

            $recipes[$i] = $recipe;
            $manager->persist($recipe);
        }

        //RecipeIngredient
        $output->writeln('RecipeIngredient');
        $unitConst = RecipeIngredient::getAvailableUnits();
        foreach ($recipes as $recipe) {
            $ingredientNumber = mt_rand(1, 10);
            $ingredientAdded = 0;
            foreach ($ingredients as $ingredient) {
                // 1 out of 10
                if (mt_rand(0, 9) === 0) {
                    $recipeIngredient = new RecipeIngredient();
                    $recipeIngredient->setRecipe($recipe)
                        ->setIngredient($ingredient)
                        ->setQuantity(mt_rand(1, 5))
                        ->setUnitType($unitConst[array_rand($unitConst)]);
                    $manager->persist($recipeIngredient);
                    $ingredientAdded++ ;
                }
                if ($ingredientNumber == $ingredientAdded){
                    break;
                }
            }
        }


        //Marks
        $output->writeln('Marks');
        foreach ($users as $user) {
            foreach ($recipes as $recipe) {
                if (mt_rand(0, 1)) {
                    $mark = new Mark();
                    $mark->setUser($user)
                        ->setMark(mt_rand(1, 5))
                        ->setComment($this->faker->text(200))
                        ->setRecipe($recipe);

                    $manager->persist($mark);
                }
            }
        }

        //Contact
        $output->writeln('Contact');
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