<?php

namespace App\Controller\Api;

use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    /**
     * @param TagRepository $tagRepository
     * @return JsonResponse
     */
    public function index(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();
        $tagList = [];

        foreach ($tags as $tag) {
            $tagList[] = [
                'id' => $tag->getId(),
                'text' => $tag->getName(),
            ];
        }

        return new JsonResponse(['results' => $tagList]);
    }
}