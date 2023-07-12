<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

class UserTest extends KernelTestCase
{

    public function getEntity(): User
    {
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'sodium' => ['algorithm' => 'sodium'],
        ]);

        // retrieve the hasher using bcrypt
        $hasher = $factory->getPasswordHasher('common');

        $user = new User();
        return $user
            ->setFullName('myfullname')
            ->setPseudo('superpseudo')
            ->setEmail('mymail@gmail.com')
            ->setPassword($hasher->hash('password'))
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
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