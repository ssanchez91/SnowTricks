<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\FigureRepository;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Valid;

class CommentController extends AbstractController
{
    /**
     * @Route("/addComment", name="add_comment")
     */
    public function addComment(Request $request, CommentService $commentService, SerializerInterface $serializerInterface): JsonResponse
    {
        if(!$this->getUser())
        {
            return new JsonResponse(['code'=> 403, 'message'=>'Unauthorized'], 403);
        }

        return new JsonResponse($serializerInterface->serialize($commentService->addComment($request->request->get('comment'), $request->request->get('figure')), 'json', array('groups' => 'tricks:read')), 200, []);
    }
}
