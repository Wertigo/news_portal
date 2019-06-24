<?php

namespace App\Controller\Api;

use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    /**
     * @param Request       $request
     * @param TagRepository $tagRepository
     *
     * @return JsonResponse
     */
    public function search(Request $request, TagRepository $tagRepository)
    {
        $searchName = $request->request->get('search', null);

        if (null === $searchName) {
            $tags = $tagRepository->findAll();
        } else {
            $tags = $tagRepository->findBySimilarName($searchName);
        }

        $serializedTags = [];

        /** @var \App\Entity\Tag $tag */
        foreach ($tags as $tag) {
            $serializedTags[] = [
                'id' => $tag->getId(),
                'text' => $tag->getName(),
            ];
        }

        return new JsonResponse(['items' => $serializedTags]);
    }
}
