<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Form\CommentType;
use App\Form\FigureType;
use App\Form\EditFigureType;
use App\Repository\FigureRepository;
use App\Service\FigureService;
use App\Service\FileUploaderService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\InstanceofConfigurator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureController extends AbstractController
{
    /**
     * @var FigureRepository
     */
    private $figureRepository;


    public function __construct(FigureRepository $figureRepository)
    {
        $this->figureRepository = $figureRepository;
    }

    /**
     * @Route("/show/{slug}", name="showFigure")
     */
    public function showFigure($slug, Request $request): Response
    {
        $figure = $this->figureRepository->findOneBy(array('slug'=>$slug));
        if(!$figure)
        {
            $this->addFlash('warning', 'This trick doesn\'t exist !');
            return $this->redirectToRoute('home');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        return $this->render('figure/showFigure.html.twig', [
            'figure' => $figure,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="delete_tricks")
     */
    public function deleteTricks($slug)
    {
        $figure = $this->figureRepository->findOneBy(['slug' => $slug]);
        $em = $this->getDoctrine()->getManager();

        if($figure && ($this->getUser() == $figure->getUser()))
        {
            $em->remove($figure);
            $em->flush();
            $this->addFlash("success", 'Your trick has just been deleted !');
        }
        else if(!$figure)
        {
            $this->addFlash("warning", 'This trick doesn\'t exist !');
        }
        else if($this->getUser() != $figure->getUser())
        {
            $this->addFlash("danger", 'You are not authorized to delete this trick ! ');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/addTrick", name="add_trick")
     */
    public function addTrick(Request $request, FigureService $figureService): Response
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $figure = $figureService->addTrick($form, $request);
            $this->addFlash('success', 'Your Trick '. $figure->getName() .' has just been added !');
            return $this->redirectToRoute('home');
        }

        return $this->render('figure/addTrick.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="edit_trick")
     */
    public function editTrick(Request $request, FigureService $figureService, FigureRepository $figureRepository, string $slug, SluggerInterface $sluggerInterface)
    {
        $figure = $figureRepository->findOneBy(array('slug'=>$slug));
        $form = $this->createForm(EditFigureType::class, $figure);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try
            {
                $updateFigure = $figureService->updateTrick($figure);
                $this->addFlash('success', 'Your Trick '. $updateFigure->getName() .' has just been updated !');
                return $this->redirectToRoute('showFigure', ['slug' => $updateFigure->getSlug()]);
            }
            catch(Exception $e)
            {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('edit_trick', ['slug' => $figure->getSlug()]);
            }
        }
        return $this->render('figure/editTrick.html.twig', [
            'form' => $form->createView(),
            'pictures' => $figure->getPictures(),
            'movies' => $figure->getMovies()
        ]);
    }

}
