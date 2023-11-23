<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Output\ConsoleOutput;

class ContactFixture extends Fixture
{
    public const CONTACT_COUNT = 5;

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $output = new ConsoleOutput();
        
        //Contact
        $output->writeln('ContactFixture > Start reached');

        for ($i = 1; $i <= self::CONTACT_COUNT; $i++) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setSubject('Demande n°' . ($i + 1))
                ->setMessage($this->faker->text());
            $manager->persist($contact);
        }
        $output->writeln('Flushing !');
        $manager->flush();
        $manager->clear();

        $output->writeln('ContactFixture > End reached');
    }
}