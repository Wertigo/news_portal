<?php

namespace App\Controller;

use App\Entity\Post;
use App\Factory\PostFactory;
use App\Factory\TagFactory;
use App\Form\PostFormType;
use App\Repository\TagRepository;
use App\Service\PostService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

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
     * @param TagRepository $tagRepository
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
    public function createPost(Request $request, PostFactory $postFactory, TagFactory $tagFactory)
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
     * @return Response
     */
    public function view(Post $post): Response
    {
        if (!$post->isPostPublished()) {
            throw $this->createNotFoundException('Post not published.');
        }

        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function viewTemplate(Post $post): Response
    {
        return $this->render('post/view-template.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function update(Request $request, Post $post)
    {
        $this->denyAccessUnlessGranted('update', $post);
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveRelatedTags($post, $request);

            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('success', 'Post draft - created.');

            return $this->redirectToRoute('view-post', [
                'post' => $post->getId(),
            ]);
        }

        return $this->render('post/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @param PostService $postService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moderatePost(Request $request, Post $post, PostService $postService, LoggerInterface $logger)
    {
        if (!$post->isPostCanBeModerate()) {
            throw $this->createNotFoundException('Post can\'t be published, because have other status');
        }

        try {
            $postService->sendPostToModerate($post);
            $this->addFlash('success', "Post {$post->getId()} was sending to moderate check.");
        } catch (\Exception $e) {
            $logger->error($e->getMessage(), [__CLASS__, __METHOD__]);
            $this->addFlash(
                'error',
                "Post {$post->getId()} not sending to moderate, please contact site administrator."
            );
        }

        $returnUrl = $request->headers->get('referer', false);

        if (!$returnUrl) {
            return $this->redirectToRoute('index');
        }

        return $this->redirect($returnUrl);
    }
}
