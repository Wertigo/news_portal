<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return Response
     */
    public function myProfile(): Response
    {
        $user = $this->getUser();

        return $this->render('user/my-profile.html.twig', [
            'user' => $user,
            'rating' => $this->userService->calculateRating($user),
        ]);
    }

    /**
     * @param User           $author
     * @param PostRepository $postRepository
     *
     * @return Response
     */
    public function authorView(User $author, PostRepository $postRepository)
    {
        return $this->render('user/author-view.html.twig', [
            'author' => $author,
            'rating' => $this->userService->calculateRating($author),
            'publications' => $postRepository->findPublishedPostsByAuthor($author),
        ]);
    }
}
