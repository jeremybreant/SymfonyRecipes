<?php

namespace App\Tests\Unit;

use App\Entity\Contact;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\ConstraintViolation;

class ContactTest extends KernelTestCase
{
    protected array $entityFieldsRestrictions = array(
        "email" => array(
            'type' => 'Symfony\Component\Validator\Constraints\Email'
        )
    );

    public function getEntity(): Contact
    {
        $contact = new Contact();
        return $contact
            ->setEmail('test@email.com')
            ->setFullName('fullNameTest')
            ->setMessage('This is a test of a contact message used for the test')
            ->setSubject('The subject test')
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

                $this->assertSame($error->getConstraint()->message, 'This value is not a valid email address.');
                $this->assertSame($error->getConstraint()->mode, 'html5');
    }
//*/

    public function testLengthRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $contact = $this->getEntity()
            ->setFullName('x')
            ->setEmail("01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789@gmail.com")
            ->setSubject('x');
        $errors = $container->get('validator')->validate($contact);
        foreach ($errors as $error){
            if($error->getPropertyPath() === "email") {
                $this->assertSame($error->getConstraint()->max, 180);
            }
            if($error->getPropertyPath() === "fullName")
            {
                $this->assertSame($error->getConstraint()->min, 2);
                $this->assertSame($error->getConstraint()->max, 50);
            }
            if($error->getPropertyPath() === "subject")
            {
                $this->assertSame($error->getConstraint()->min, 2);
                $this->assertSame($error->getConstraint()->max, 100);
            }
        }
    }

    /*
    public function testNotNullRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    }

    public function testNotBlankRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    }
    //*/
}