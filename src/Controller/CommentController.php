<?php

namespace App\Controller;


use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * CommentController class
 */
class CommentController extends AbstractController
{
    /**
     * Add a comment on trick
     *
     * @Route("/addComment", name="add_comment")
     * 
     * @param Request $request
     * @param CommentService $commentService
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */
    public function addComment(Request $request, CommentService $commentService, SerializerInterface $serializerInterface): JsonResponse
    {
        if (!$this->getUser())
        {
            return new JsonResponse(['code' => 403, 'message' => 'Unauthorized'], 403);
        }

        return new JsonResponse($serializerInterface->serialize($commentService->addComment($request->request->get('comment'), $request->request->get('figure')), 'json', array('groups' => 'tricks:read')), 200, []);
    }
}
