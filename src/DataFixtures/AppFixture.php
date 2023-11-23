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
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use Symfony\Component\Console\Output\ConsoleOutput;
use function PHPUnit\Framework\isNull;

class AppFixture extends Fixture implements DependentFixtureInterface
{

    /**
     * @return list<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
            ContactFixture::class,
            IngredientFixture::class,
            //MarkFixture::class,
            RecipeFixture::class,
            //RecipeIngredientFixture::class,
            UserFixture::class
        ];
    }

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

        $output->writeln('End');


    }
}