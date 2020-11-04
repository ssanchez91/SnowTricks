<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DefaultController class
 */
class DefaultController extends AbstractController
{
    /**
     * Show Home Page (return listFigure)
     *
     * @Route("/home", name="home")
     * 
     * @param PictureRepository $pictureRepository
     * @return void
     */
    public function homeAction(PictureRepository $pictureRepository): Response
    {
        $listFigure = $pictureRepository->findBy(array('defaultPicture' => true));

        return $this->render('default/default.html.twig', [
            'listFigure' => $listFigure
        ]);
    }
}
