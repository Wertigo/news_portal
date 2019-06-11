<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function activateAccount(User $user): bool
    {
        if (!$user) {
            $this->logger->error('No user presenterd for activation');

            return false;
        }

        $user->setStatus(User::STATUS_ACTIVE);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }
}