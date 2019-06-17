<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAllActivatedUsers();
        $userList = [];

        /** @var User $user */
        foreach ($users as $user) {
            $userList[] = [
                'id' => $user->getId(),
                'text' => $user->getName(),
            ];
        }

        return new JsonResponse(['results' => $userList]);
    }
}