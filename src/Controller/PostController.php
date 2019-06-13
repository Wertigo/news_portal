<?php

namespace App\Controller;

use App\Entity\Post;
use App\Factory\PostFactory;
use App\Form\PostFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    /**
     * @param Request $request
     * @param PostFactory $postFactory
     * @return mixed
     */
    public function createPost(Request $request, PostFactory $postFactory)
    {
        $post = $postFactory->createNew();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $this->getUser();
            $post->setAuthor($author);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'Post draft - created.');

            return $this->redirectToRoute('view-post', [
                'post' => $post->getId(),
            ]);
        }

        return $this->render('post/create-post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function view(Post $post): Response
    {
        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }
}
