<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
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
     */
    public function search(Request $request, \Symfony\Component\Serializer\SerializerInterface $serializer): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $usersArray = [];

        foreach ($users as $user) {
            $usersArray[] = $user->toArray();
        }

        return new JsonResponse(['users' => $usersArray]);
    }
}
