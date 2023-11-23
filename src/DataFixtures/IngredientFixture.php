<?php
declare(strict_types=1);

namespace App\DataFixtures;


use Faker\Factory;
use Faker\Generator;
use App\Entity\Category;
use App\Entity\Ingredient;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class IngredientFixture extends Fixture implements DependentFixtureInterface
{

    public const INGREDIENT = 'ingredient';
    public const INGREDIENT_COUNT = 300;
    /**
     * @return list<class-string<<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
            UserFixture::class
        ];
    }

    /**
     * @var Generator
     */
    private Generator $faker;

    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->userRepository = $userRepository;
    }
    public function load(ObjectManager $manager): void
    {

        $output = new ConsoleOutput();
        
        $users = $this->userRepository->findAll();

        //Ingredients
        $output->writeln('IngredientFixture > Start reached');

        for ($i = 1; $i <= self::INGREDIENT_COUNT; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setUser($users[mt_rand(0, count($users) - 1)]);
            $manager->persist($ingredient);
            $this->addReference(self::INGREDIENT.$i, $ingredient);
        }
        $output->writeln('Flushing !');
        $manager->flush();
        $manager->clear();

        $output->writeln('IngredientFixture > End reached');
    }
}