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

class PictureController extends AbstractController
{
    /**
     * @Route("/picture/delete/{id}", name="picture_delete", methods={"DELETE"})
     */
    public function pictureDelete(Request $request, Picture $picture, PictureService $pictureService): JsonResponse
    {
        if($this->isCsrfTokenValid('delete_', $request->request->get('_token'))){

            if($pictureService->deletePicture($picture))
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
     * @Route("/picture/default/{id}", name="picture_default", methods={"POST"})
     */
    public function pictureDefault(Request $request, Picture $picture, PictureService $pictureService): JsonResponse
    {
        if($this->isCsrfTokenValid('default_', $request->request->get('_token')))
        {
            if($pictureService->defaultPicture($picture))
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
     * @Route("/picture/add/{id}", name="picture_add", methods={"POST"})
     */
    public function pictureAdd(Request $request, Figure $figure , PictureService $pictureService, FileUploaderService $fileUploaderService, SerializerInterface $serializerInterface): JsonResponse
    {
        $new_picture = $request->files->get('file');

        if(!empty($new_picture) && ($new_picture->guessExtension() == 'png' || $new_picture->guessExtension() == 'jpeg' || $new_picture->guessExtension() == 'jpg' ))
        {
            $picture = $pictureService->addPicture($new_picture, $figure);
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
