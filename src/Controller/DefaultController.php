<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function homeAction()
    {
        return $this->render('default/default.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
}
