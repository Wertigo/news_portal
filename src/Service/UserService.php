<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class UserService
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UserService constructor.
     *
     * @param ObjectManager          $manager
     * @param LoggerInterface        $logger
     */
    public function __construct(ObjectManager $manager, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function activateAccount(User $user): void
    {
        $user->setStatus(User::STATUS_ACTIVE);
        $this->manager->persist($user);
        $this->manager->flush();
    }

    /**
     * @param User $user
     *
     * @return float
     */
    public function calculateRating(User $user): float
    {
        $posts = $user->getPosts();

        $rating = 0.0;

        foreach ($posts as $post) {
            $rating += $post->getRating();
        }

        return $rating;
    }
}
