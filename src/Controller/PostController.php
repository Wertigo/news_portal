<?php

namespace App\Controller;

use App\Entity\Post;
use App\Factory\PostFactory;
use App\Form\PostFormType;
use App\Repository\TagRepository;
use App\Service\PostService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * PostController constructor.
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager, TagRepository $tagRepository)
    {
        $this->entityManager = $entityManager;
        $this->tagRepository = $tagRepository;
    }

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
            $this->saveRelatedTags($post, $request);

            $this->entityManager->persist($post);
            $this->entityManager->flush();
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
     * @param $tags
     */
    private function saveRelatedTags(Post $post, Request $request)
    {
        $submitedTagIds = $request->get('post_form')['post_tags'];
        $post->setTags($this->tagRepository->findBy(['id' => $submitedTagIds]));
    }

    /**
     * @param Post $post
     * @param PostService $postService
     * @return Response
     */
    public function view(Post $post, PostService $postService): Response
    {
        if (!$postService->isPostPublished($post)) {
            throw $this->createNotFoundException('Post not published.');
        }

        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }

    public function viewTemplate(Post $post): Response
    {
        return $this->render('post/view-template.html.twig', [
            'post' => $post,
        ]);
    }
}
