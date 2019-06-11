<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    /**
     * @return Response
     */
    public function createPost(): Response
    {
        return $this->render('post/create-post.html.twig', [

        ]);
    }
}
