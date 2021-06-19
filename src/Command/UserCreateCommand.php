<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'app:user-create';
    private EntityManagerInterface $entityManager;
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(EntityManagerInterface $entityManager, PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasherFactory = $passwordHasherFactory;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create an User')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('role', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'User role', ['ROLE_ADMIN'])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $roles = $input->getArgument('role');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $hashedPassword = $passwordHasher->hash($password);

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setRoles($roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('User created.');

        return 0;
    }
}
