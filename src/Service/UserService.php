<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UserService
{
    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @return bool
     */
    public function activateAccount(User $user, EntityManagerInterface $entityManager, LoggerInterface $logger): bool
    {
        if ($user) {
            $user->setStatus(User::STATUS_ACTIVE);
            $entityManager->persist($user);
            $entityManager->flush();
        } else {
            $logger->error('No user presenterd for activation');
        }
    }
}