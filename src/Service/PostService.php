<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class PostService
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ObjectManager $manager, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->logger = $logger;
    }

    /**
     * @param Post $post
     * @return bool
     *
     * @throws \Exception
     */
    public function sendPostToModerate(Post $post): bool
    {
        if (!$post->isPostCanBeModerate()) {
            $this->logger->error("Post can't be send to moderate, because have incorrect status");
            return false;
        }

        $post->setStatus(Post::STATUS_MODERATION_CHECK);
        $this->manager->persist($post);
        $this->manager->flush();

        return true;
    }

    /**
     * @param Post $post
     *
     * @return void
     */
    public function publishPost(Post $post): void
    {
        $post->setStatus(Post::STATUS_PUBLISHED);
        $this->manager->persist($post);
        $this->manager->flush();
    }

    /**
     * @param Post $post
     *
     * @return void
     */
    public function declinePost(Post $post): void
    {
        $post->setStatus(Post::STATUS_DECLINED);
        $this->manager->persist($post);
        $this->manager->flush();
    }
}
