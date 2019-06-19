<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class PostController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * PostController constructor.
     *
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function ratingUp(Post $post): JsonResponse
    {
        $post->setRating($post->getRating() + 1);
        $this->manager->persist($post);
        $this->manager->flush();

        return $this->json(['rating' => $post->getRating()]);
    }

    /**
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function ratingDown(Post $post): JsonResponse
    {
        $post->setRating($post->getRating() - 1);
        $this->manager->persist($post);
        $this->manager->flush();

        return $this->json(['rating' => $post->getRating()]);
    }
}
