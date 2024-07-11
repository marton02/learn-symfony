<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user account',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $manager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $helper = $this->getHelper('question');

        $questionemail = new Question('What will be the email for the new user? ');
        $questionemail->setHidden(false);
        $questionemail->setHiddenFallback(false);

        $email = $helper->ask($input, $output, $questionemail);

        $questionpwd = new Question('What will be the password for the new user? ');
        $questionpwd->setHidden(true);
        $questionpwd->setHiddenFallback(false);

        $pwd = $helper->ask($input, $output, $questionpwd);

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $pwd
            )
        );

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success(sprintf('User %s account created!',$email));

        return Command::SUCCESS;
    }
}
