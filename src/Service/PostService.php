<?php

namespace App\Service;

use App\Entity\Post;

class PostService
{
    /**
     * @param Post $post
     * @return bool
     */
    public function isPostPublished(Post $post)
    {
        return Post::STATUS_PUBLISHED === $post->getStatus();
    }
}