<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    /**
     * @var int
     */
    const MAX_ELEMENTS_ON_INDEX_PAGE = 10;

    /**
     * @param PostRepository $postRepository
     *
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        return $this->render('index/index.html.twig', [
            'posts' => $postRepository->findPostsForIndexPage(static::MAX_ELEMENTS_ON_INDEX_PAGE),
        ]);
    }
}
