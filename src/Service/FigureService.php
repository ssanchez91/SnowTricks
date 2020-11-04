<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 20/10/2020
 * Time: 19:04
 */

namespace App\Service;

use App\Entity\Figure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * FigureService class
 */
class FigureService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;
    /**
     * @var FileUploaderService
     */
    private $fileUploaderService;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var AsciiSlugger
     */
    private $asciiSlugger;
    /**
     * @var SluggerInterface
     */
    private $sluggerInterface;


    /**
     * __construct FigureService
     *
     * @param EntityManagerInterface $entityManagerInterface
     * @param FileUploaderService $fileUploaderService
     * @param Security $security
     * @param SluggerInterface $sluggerInterface
     */
    public function __construct(EntityManagerInterface $entityManagerInterface, FileUploaderService $fileUploaderService, Security $security, SluggerInterface $sluggerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->fileUploaderService = $fileUploaderService;
        $this->security = $security;
        $this->sluggerInterface = $sluggerInterface;
    }

    /**
     * Create new trick
     *
     * @param FormInterface $form
     * @param Request $request
     * @return Figure
     */
    public function addTrick(FormInterface $form, Request $request): Figure
    {
        $figure = $form->getData();
        $mainPicture = $this->uploadMainPicture($form, $request, $figure);
        $this->uploadSecondaryPictures($form);

        /*get mainMovie and set one */
        $mainMovie = $form->get('mainMovie')->getData();
        $mainMovie->setFigure($figure);

        /*Set Figure before persist in database*/

        $persistdate = new \DateTime();
        $figure->setSlug($this->sluggerInterface->slug($figure->getName()));
        $figure->addPicture($mainPicture);
        $figure->addMovie($mainMovie);
        $figure->setUser($this->security->getUser());
        $figure->setCreatedAt($persistdate);
        $figure->setUpdatedAt($persistdate);
        $this->entityManagerInterface->persist($figure);
        $this->entityManagerInterface->flush();

        return $figure;
    }

    /**
     * Upload main file picture and set property path in picture
     *
     * @param FormInterface $form
     * @param Request $request
     * @param Figure $figure
     * @return mixed
     */
    private function uploadMainPicture(FormInterface $form, Request $request, Figure $figure)
    {
        /** @var UploadedFile $mainPictureFile */
        $mainPictureFile = $request->files->get('figure')['mainPicture']['path'];

        /*Set mainPicture */
        $mainPicture = $form->get('mainPicture')->getData();
        $mainPicture->setPath($this->fileUploaderService->upload($mainPictureFile, 'tricks'));
        $mainPicture->setDefaultPicture(true);
        $mainPicture->setFigure($figure);

        return $mainPicture;
    }

    /**
     * Upload others files picture
     *
     * @param FormInterface $form
     */
    private function uploadSecondaryPictures(FormInterface $form): void
    {
        /* Get Secondary Pictures forms */
        $formSecondaryPictures = $form->get('pictures')->all();
        foreach($formSecondaryPictures as $formSecondaryPicture)
        {
            $file = $formSecondaryPicture->get('path')->getData();
            if ($file instanceof UploadedFile)
            {
                $picture = $formSecondaryPicture->getData();
                $picture->setPath($this->fileUploaderService->upload($file, 'tricks'));
            }
        }
    }

    /**
     * updateTrick
     *
     * @param Figure $figure
     * @return Figure
     */
    public function updateTrick(Figure $figure): Figure
    {
        $figure->setSlug($this->sluggerInterface->slug($figure->getName()));
        $this->entityManagerInterface->persist($figure);
        $this->entityManagerInterface->flush();

        return $figure;
    }
}