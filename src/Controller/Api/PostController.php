<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @param Request $request
     * @param Post $post
     * @return array
     */
    public function searchTagsExceptPostTags(Request $request, Post $post)
    {
        return [];
    }
}