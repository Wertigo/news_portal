<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @var int
     */
    const SEARCH_PAGE_SIZE = 10;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     */
    public function searchByEmail(Request $request): JsonResponse
    {
        $searchString = $request->request->get('search', null);

        if (null === $searchString) {
            $users = $this->userRepository->findAll();
        } else {
            $users = $this->userRepository->findBySimilarEmail($searchString);
        }

        $serializedUsers = [];

        /** @var \App\Entity\User $user */
        foreach ($users as $user) {
            $serializedUsers[] = [
                'id' => $user->getId(),
                'text' => $user->getEmail(),
            ];
        }

        return new JsonResponse(['items' => $serializedUsers]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function search(Request $request): JsonResponse
    {
        $paginationParams = [
            'limit' => $request->query->get('page_size', static::SEARCH_PAGE_SIZE),
            'page' => $request->query->get('page', 1),
            'email' => $request->query->get('email', null),
            'user_have_posts' => $request->query->get('user_have_posts', null),
            'user_have_comments' => $request->query->get('user_have_comments', null),
        ];

        return new JsonResponse($this->getUserSearchPagination($paginationParams));
    }

    /**
     * @param array $params
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getUserSearchPagination($params)
    {
        $email = $params['email'];
        $items = [];
        $nextPage = null;
        $prevPage = null;
        $currentPage = (int) $params['page'];
        $limit = (int) $params['limit'];
        $offset = (int) ($currentPage - 1) * $limit;
        $queryParams = [
            'email' => $email,
            'offset' => $offset,
            'limit' => $limit,
            'user_have_posts' => (bool) $params['user_have_posts'],
            'user_have_comments' => (bool) $params['user_have_comments'],
        ];
        $totalCount = $this->userRepository->findAllWithOffsetAndLimitByEmail($queryParams, true);
        $totalPages = (int) $totalCount / $limit;
        $userModels = $this->userRepository->findAllWithOffsetAndLimitByEmail($queryParams, false);

        if ($totalCount > ($offset + $limit)) {
            $nextPage = $currentPage + 1;
        }

        if ($offset > 0 && $currentPage !== 1) {
            $prevPage = $currentPage - 1;
        }

        /** @var User $userModel */
        foreach ($userModels as $userModel) {
            $items[] = $userModel->toArray();
        }

        if (($totalPages === 0 && count($items) > 0) ||
            $totalPages < 1
        ) {
            $totalPages = 1;
        }

        return [
            'items' => $items,
            'next_page' => $nextPage,
            'prev_page' => $prevPage,
            'total_items' => $totalCount,
            'current_page' => $currentPage,
            'page_size' => $limit,
            'total_pages' => $totalPages,
        ];
    }
}
