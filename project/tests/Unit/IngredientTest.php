<?php

namespace App\Tests\Unit;

use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngredientTest extends KernelTestCase
{
    public function getEntity(): Ingredient
    {
        $ingredient = new Ingredient();
        return $ingredient
            ->setName('testName')
            ->setPrice(4.6)
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
