<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PublicationsFormType;
use App\Form\UserListSearchType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @var int
     */
    const PAGINATION_PUBLICATIONS_LIMIT_PER_PAGE = 10;

    /**
     * @var int
     */
    const PAGINATION_USER_LIST_LIMIT_PER_PAGE = 10;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * AdminController constructor.
     *
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param PostRepository     $postRepository
     *
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
            static::PAGINATION_PUBLICATIONS_LIMIT_PER_PAGE
        );

        return $this->render('admin/publications.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    private function getPage(Request $request)
    {
        return $request->query->getInt('page', 1);
    }

    /**
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function publishPost(Post $post)
    {
        $this->postService->publishPost($post);

        return $this->getRedirectToPostTemplatePage($post);
    }

    /**
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getRedirectToPostTemplatePage(Post $post)
    {
        return $this->redirectToRoute('view-template-post', ['post' => $post->getId()]);
    }

    /**
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function declinePost(Post $post)
    {
        $this->postService->declinePost($post);

        return $this->getRedirectToPostTemplatePage($post);
    }

    /**
     * @param Request            $request
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userList(Request $request, UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $form = $this->createForm(UserListSearchType::class);
        $form->handleRequest($request);
        $query = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $params = $form->getData();
            $query = $userRepository->findAllQuery($params);
        }

        $pagination = $paginator->paginate(
            null === $query ? $userRepository->findAllQuery() : $query,
            $this->getPage($request),
            static::PAGINATION_USER_LIST_LIMIT_PER_PAGE
        );

        return $this->render('admin/user-list.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}
