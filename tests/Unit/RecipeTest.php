<?php

namespace App\Tests\Unit;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    public function getEntity(): Recipe
    {
        $recipe = new Recipe();
        return $recipe
            ->setName('This the name of my beautifull recipe')
            ->setDescription('This is the description of a beautifull recipe that i have made')
            ->setPrice(4.0)
            ->setDifficulty(4)
            ->setFoodQuantity(5)
            ->setTime(120)
            ->setIsPublic(true)
            ->setIsFavorite(true)
            ->setCreatedAt(new \DateTimeImmutable());


    }

    public function testIsSampleEntityValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $errors = $container->get('validator')->validate($this->getEntity());

        $this->assertCount(0, $errors);
    }

    /*
    public function testTypeRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    }
    //*/
    /*
    public function testLengthRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    }
    //*/
    /*
    public function testNotNullRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    }
    //*/
    /*
    public function testNotBlankRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    }
    //*/
}
