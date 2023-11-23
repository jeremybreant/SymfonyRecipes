<?php
declare(strict_types=1);

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Console\Output\ConsoleOutput;

class UserFixture extends Fixture
{

    public const USER = 'user';
    public const USER_COUNT = 10;
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

                //Users
                $output->writeln('UserFixture > Start reached');

                $admin = new User();
                $admin->setFullName('Administrateur de SymRecipe')
                    ->setPseudo($this->faker->firstName())
                    ->setEmail('admin@symrecipe.fr')
                    ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
                    ->setPlainPassword('password');
                $manager->persist($admin);
                $this->addReference(self::USER."1", $admin);
                
        
                for ($i = 2; $i <= self::USER_COUNT; $i++) {        
                    $user = new User();
                    $user->setFullName($this->faker->name())
                        ->setPseudo($this->faker->firstName())
                        ->setEmail($this->faker->email())
                        ->setRoles(['ROLE_USER'])
                        ->setPlainPassword('password');
        
                    $manager->persist($user);
                    $this->addReference(self::USER.$i, $user);
                }
                $output->writeln('Flushing !');
                $manager->flush();
                $manager->clear();

                $output->writeln('UserFixture > End reached');
    }
}