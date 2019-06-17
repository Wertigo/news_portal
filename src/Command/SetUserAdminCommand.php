<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SetUserAdminCommand extends Command
{
    protected static $defaultName = 'user:set-user-admin';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(?string $name = null, UserRepository $userRepository, ObjectManager $objectManager)
    {
        parent::__construct($name);

        $this->userRepository = $userRepository;
        $this->manager = $objectManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Change user role to admin')
            ->addArgument('userId', InputArgument::OPTIONAL, 'User id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $userId = $input->getArgument('userId');
        $user = $this->userRepository->find($userId);

        if (!$user) {
            $io->error("No user with id $userId");
            exit(-1);
        }

        $user->setRoles([User::ROLE_ADMIN]);
        $this->manager->persist($user);
        $this->manager->flush();

        $io->writeln("User with $userId now - admin");
    }
}
