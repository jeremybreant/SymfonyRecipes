<?php

namespace App\Tests\Unit;

use App\Entity\Contact;
use App\Tests\TestConstant;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Event\Code\Throwable;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\ConstraintViolation;
use TypeError;

class ContactTest extends KernelTestCase
{

    public function getEntity(): Contact
    {
        $contact = new Contact();
        return $contact
            ->setEmail(TestConstant::VALID_MAIL)
            ->setFullName(TestConstant::VALID_FULLNAME)
            ->setMessage(TestConstant::VALID_CONTACT_MESSAGE)
            ->setSubject(TestConstant::VALID_CONTACT_SUBJECT)
            ->setCreatedAt(new \DateTimeImmutable());
    }

    public function testGetterSetter(): void
    {
        $container = static::getContainer();

        $contact = new Contact();

        $mail = 'mail';
        $fullname = 'Name';
        $subject = 'Subject';
        $message = 'Message';
        $createdAt = new \DateTimeImmutable();

        $contact
            ->setEmail($mail)
            ->setFullName($fullname)
            ->setSubject($subject)
            ->setMessage($message)
            ->setCreatedAt($createdAt);

        $this->assertTrue($contact->getEmail() === $mail);
        $this->assertTrue($contact->getFullName() === $fullname);
        $this->assertTrue($contact->getSubject() === $subject);
        $this->assertTrue($contact->getMessage() === $message);
        $this->assertTrue($contact->getCreatedAt() === $createdAt);
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
        $container = static::getContainer();

        $contact = $this->getEntity()
            ->setFullName('x')
            ->setEmail("01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789@gmail.com")
            ->setSubject('x');
        $errors = $container->get('validator')->validate($contact);

        foreach ($errors as $error){
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_MAIL) {
                $this->assertSame($error->getConstraint()->max, 180);
            }
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_FULLNAME)
            {
                $this->assertSame($error->getConstraint()->min, 2);
                $this->assertSame($error->getConstraint()->max, 50);
            }
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_CONTACT_SUBJECT)
            {
                $this->assertSame($error->getConstraint()->min, 2);
                $this->assertSame($error->getConstraint()->max, 100);
            }
        }
    }


    public function testNotNullRestrictions(): void
    {
        $container = static::getContainer();
        $contact = new Contact();

        $mail = null;
        $fullname = "x";
        $subject = null;
        $message = null;
        $createdAt = null;


        $contact
            ->setEmail($mail)
            ->setFullName($fullname)
            ->setMessage($message)
            ->setSubject($subject)
            ->setCreatedAt($createdAt);

        $errors = $container->get('validator')->validate($contact);
        /*
        foreach ($errors as $error){
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_MAIL) {
                $this->assertFalse($error->getConstraint()->allowNull);
            }
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_FULLNAME)
            {
                $this->assertFalse($error->getConstraint()->allowNull);
            }
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_CREATEDAT)
            {
                $this->assertFalse($error->getConstraint()->allowNull);
            }
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_CONTACT_SUBJECT)
            {
                $this->assertFalse($error->getConstraint()->allowNull);
            }
            if($error->getPropertyPath() === TestConstant::PROPERTYPATH_CONTACT_MESSAGE)
            {
                $this->assertFalse($error->getConstraint()->allowNull);
            }
        }
        //*/
        //$this->assertCount(5,$errors);
    }

    public function testNotBlankRestrictions(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    }

}