<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MarkTest extends KernelTestCase
{
    public function getEntity(): Mark
    {
        $mark = new Mark();
        return $mark
            ->setMark(4.0);
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
