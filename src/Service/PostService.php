<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\Common\Persistence\ObjectManager;

class PostService
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Post $post
     *
     * @throws \Exception
     */
    public function sendPostToModerate(Post $post): void
    {
        if (!$post->isPostCanBeModerate()) {
            throw new \Exception('Post can\'t be send to moderate, because have incorrect status');
        }

        $post->setStatus(Post::STATUS_MODERATION_CHECK);
        $this->manager->persist($post);
        $this->manager->flush();
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function publishPost(Post $post): bool
    {
        try {
            $post->setStatus(Post::STATUS_PUBLISHED);
            $this->manager->persist($post);
            $this->manager->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function declinePost(Post $post): bool
    {
        try {
            $post->setStatus(Post::STATUS_DECLINED);
            $this->manager->persist($post);
            $this->manager->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
