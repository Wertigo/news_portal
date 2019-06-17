<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PublicationsFormType;
use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @var int
     */
    const PAGINATION_LIMIT_PER_PAGE = 10;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * AdminController constructor.
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param PostRepository $postRepository
     * @return mixed
     */
    public function publications(Request $request, PaginatorInterface $paginator, PostRepository $postRepository)
    {
        $form = $this->createForm(PublicationsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $params = $form->getData();
            $query = $postRepository->findAllExceptDrafts($params, true);
        }

        $pagination = $paginator->paginate(
            $query ?? $postRepository->findAllExceptDrafts([], true),
            $this->getPage($request),
            static::PAGINATION_LIMIT_PER_PAGE
        );

        return $this->render('admin/publications.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return int
     */
    private function getPage(Request $request)
    {
        return $request->query->getInt('page', 1);
    }

    /**
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function publishPost(Post $post)
    {
        $this->postService->publishPost($post);

        return $this->redirectToRoute('admin-publications');
    }

    /**
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function declinePost(Post $post)
    {
        $this->postService->declinePost($post);

        return $this->redirectToRoute('admin-publications');
    }
}