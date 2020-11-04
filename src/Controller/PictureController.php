<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Service\FileUploaderService;
use App\Service\PictureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * PictureController class
 */
class PictureController extends AbstractController
{
    /**
     * @var PictureService
     */
    private $pictureService;

    /**
     * PictureController constructor.
     */
    public function __construct(PictureService $pictureService)
    {
        $this->pictureService = $pictureService;
    }

    /**
     * Delete picture
     * 
     * @Route("/picture/delete/{id}", name="picture_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Picture $picture
     * @return JsonResponse
     */
    public function pictureDelete(Request $request, Picture $picture): JsonResponse
    {
        if($this->isCsrfTokenValid('delete_', $request->request->get('_token'))){

            if($this->pictureService->deletePicture($picture))
            {
                return new JsonResponse(['success' => 1, "default_picture" => false ]);
            }
            else
            {
                return new JsonResponse(['success' => 1, "default_picture" => true ]);
            }
        }
        else
        {
            return new JsonResponse(['error' => 'Token is not valid !'], 400);
        }
    }

    /**
     * Change the default picture on a trick
     * 
     * @Route("/picture/default/{id}", name="picture_default", methods={"POST"})
     *
     * @param Request $request
     * @param Picture $picture
     * @return JsonResponse
     */
    public function pictureDefault(Request $request, Picture $picture): JsonResponse
    {
        if($this->isCsrfTokenValid('default_', $request->request->get('_token')))
        {
            if($this->pictureService->defaultPicture($picture))
            {
                return new JsonResponse(['success' => 1, "default_picture" => "updated" ]);
            }
            else
            {
                return new JsonResponse(['success' => 1, "default_picture" => "error" ]);
            }
        }
        else
        {
            return new JsonResponse(['error' => 'Token is not valid !'], 400);
        }
    }


    /**
     * Add a new picture
     * 
     * @Route("/picture/add/{id}", name="picture_add", methods={"POST"})
     *
     * @param Request $request
     * @param Figure $figure
     * @param FileUploaderService $fileUploaderService
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */
    public function pictureAdd(Request $request, Figure $figure, FileUploaderService $fileUploaderService, SerializerInterface $serializerInterface): JsonResponse
    {
        $new_picture = $request->files->get('file');

        if(!empty($new_picture) && ($new_picture->guessExtension() == 'png' || $new_picture->guessExtension() == 'jpeg' || $new_picture->guessExtension() == 'jpg' ))
        {
            $picture = $this->pictureService->addPicture($new_picture, $figure);
            if($picture)
            {
                return new JsonResponse(['success' => 1, "add_picture" => $serializerInterface->serialize($picture, 'json', ['groups'=> 'tricks:read']), 'figure' => $figure->getId() ]);
            }
            else
            {
                return new JsonResponse(['success' => 1, "add_picture" => 'error']);
            }
        }
        else
        {
            return new JsonResponse(['error' => 'You must upload a .jpg or .png file !'], 400);
        }
    }
}
