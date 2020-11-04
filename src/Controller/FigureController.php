<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Form\CommentType;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use App\Service\FigureService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * FigureController class
 */
class FigureController extends AbstractController
{
    /**
     * @var FigureRepository
     */
    private $figureRepository;

    /**
     * Constructor Figure Controller
     *
     * @param FigureRepository $figureRepository
     */
    public function __construct(FigureRepository $figureRepository)
    {
        $this->figureRepository = $figureRepository;
    }

    /**
     * Show Details Figure
     *
     * @Route("/show/{slug}", name="showFigure")
     * 
     * @param [type] $slug
     * @param Request $request
     * @return Response
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
     * Delete trick with associates medias
     * 
     * @Route("/delete/{slug}", name="delete_tricks")
     *
     * @param [type] $slug
     * @return Response
     */
    public function deleteTricks($slug): Response
    {
        $figure = $this->figureRepository->findOneBy(['slug' => $slug]);
        $em = $this->getDoctrine()->getManager();

        if($this->checkAcces($figure, 'delete'))
        {
            $em->remove($figure);
            $em->flush();
            $this->addFlash("success", 'Your trick has just been deleted !');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * Add new Trick
     * 
     * @Route("/addTrick", name="add_trick")
     *
     * @param Request $request
     * @param FigureService $figureService
     * @return Response
     */
    public function addTrick(Request $request, FigureService $figureService): Response
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure, ['routeName' => $request->attributes->get('_route')]);
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
     * Edit Trick
     * 
     * @Route("/edit/{slug}", name="edit_trick")
     *
     * @param Request $request
     * @param FigureService $figureService
     * @param FigureRepository $figureRepository
     * @param string $slug
     * @param SluggerInterface $sluggerInterface
     * @return void
     */
    public function editTrick(Request $request, FigureService $figureService, FigureRepository $figureRepository, string $slug, SluggerInterface $sluggerInterface): Response
    {
        $figure = $figureRepository->findOneBy(array('slug'=>$slug));

        if($this->checkAcces($figure, 'edit'))
        {
            $form = $this->createForm(FigureType::class, $figure, ['routeName' => $request->attributes->get('_route')]);
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
            return $this->render('figure/editTrick.html.twig', ['form' => $form->createView(),'pictures' => $figure->getPictures(),'movies' => $figure->getMovies()]);
        }
        return $this->redirectToRoute('home');
    }

    /**
     * Allow to verify granted access
     *
     * @param [type] $figure
     * @param string $action
     * @return boolean
     */
    public function checkAcces($figure, string $action): bool
    {
        if($figure instanceof Figure && ($this->getUser() == $figure->getUser()))
        {
            return true;
        }
        else if(!$figure)
        {
            $this->addFlash("warning", 'This trick doesn\'t exist !');
        }
        else if($this->getUser() != $figure->getUser())
        {
            $this->addFlash("danger", 'You are not authorized to '. $action .' this trick ! ');
        }

        return false;
    }

}
