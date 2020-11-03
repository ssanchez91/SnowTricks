<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Movie;
use App\Service\MovieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/delete/{id}", name="movie_delete", methods={"DELETE"})
     */
    public function movieDelete(Request $request, Movie $movie, MovieService $movieService): JsonResponse
    {
        if($this->isCsrfTokenValid('delete_movie', $request->request->get('_token')))
        {
            if($movieService->deleteMovie($movie))
            {
                return new JsonResponse(['success' => 1, "delete_movie" => true ]);
            }
            else
            {
                return new JsonResponse(['success' => 1, "delete_movie" => false ]);
            }
        }
        else
        {
            return new JsonResponse(['error' => 'Token is not valid !'], 400);
        }
    }

    /**
     * @Route("/movie/add/{id}", name="movie_add", methods={"POST"})
     */
    public function movieAdd(Request $request, Figure $figure, MovieService $movieService, SerializerInterface $serializerInterface): JsonResponse
    {
        $url = $request->request->get('movie');

        if($movieService->checkUrl($url))
        {
            $movie = $movieService->addMovie($url, $figure);
            if($movie)
            {
                return new JsonResponse(['success' => 1, "add_movie" => $serializerInterface->serialize($movie, 'json', ['groups'=> 'tricks:read']), 'figure' => $figure->getId() ]);
            }
            else
            {
                return new JsonResponse(['success' => 1, "add_movie" => 'error']);
            }
        }
        else
        {
            return new JsonResponse(['error' => 'You must enter a url in the following format: https://www.youtube.com/embed/(your video id)'], 400);
        }
    }
}
