<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-administrator',
    description: 'Add a short description for your command',
)]
class CreateAdministratorCommand extends Command
{

    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct('app:create-administrator');
        $this->manager = $manager;

    }

    protected function configure(): void
    {
        $this
            ->addArgument('full_name', InputArgument::OPTIONAL, 'Admin\'s fullname')
            ->addArgument('email', InputArgument::OPTIONAL, 'Admin\'s email')
            ->addArgument('plain_password', InputArgument::OPTIONAL, 'Admin\'s password')
            ->addArgument('pseudo', InputArgument::OPTIONAL, 'Admin\'s pseudo');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);
        $fullname = $input->getArgument('full_name');
        if (!$fullname) {
            $question = new Question('What is the fullname of the administrator : ');
            $fullname = $helper->ask($input, $output, $question);
        }

        $email = $input->getArgument('email');
        if (!$email) {
            $question = new Question('What is the email of the administrator : ');
            $email = $helper->ask($input, $output, $question);
        }

        $plainpassword = $input->getArgument('plain_password');
        if (!$plainpassword) {
            $question = new Question('What is the password of the administrator: ');
            $plainpassword = $helper->ask($input, $output, $question);
        }

        $pseudo = $input->getArgument('pseudo');
        if (!$pseudo) {
            $question = new Question('What is the pseudo of the administrator: ');
            $pseudo = $helper->ask($input, $output, $question);
        }

        $user = (new User())->setFullName($fullname)
            ->setEmail($email)
            ->setPlainPassword($plainpassword)
            ->setPseudo($pseudo)
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success('Administrator added to database');

        return Command::SUCCESS;
    }
}
