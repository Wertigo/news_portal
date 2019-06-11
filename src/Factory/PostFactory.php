<?php

namespace App\Factory;

use App\Entity\Post;

class PostFactory
{
    /**
     * @return Post
     */
    public function createNew(): Post
    {
        return (new Post())
            ->setStatus(Post::STATUS_DRAFT)
            ->setRating(0)
        ;
    }
}