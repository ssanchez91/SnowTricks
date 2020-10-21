<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function homeAction(PictureRepository $pictureRepository)
    {
        $listFigure = $pictureRepository->findBy(array('defaultPicture'=>true));

        return $this->render('default/default.html.twig', [
            'listFigure' => $listFigure
        ]);
    }
}
