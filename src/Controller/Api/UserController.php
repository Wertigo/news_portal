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
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     */
    public function searchByEmail(Request $request, UserRepository $userRepository): JsonResponse
    {
        $searchString = $request->request->get('search', null);

        if (null === $searchString) {
            return new JsonResponse([]);
        }

        $users = $userRepository->findBySimilarEmail($searchString);
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
}
