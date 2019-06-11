<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @param UserService $userService
     * @return Response
     */
    public function myProfile(UserService $userService): Response
    {
        $user = $this->getUser();

        return $this->render('user/my-profile.html.twig', [
            'user' => $user,
            'rating' => $userService->calculateRating($user),
        ]);
    }
}
