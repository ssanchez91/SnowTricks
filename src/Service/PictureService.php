<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 27/10/2020
 * Time: 17:15
 */

namespace App\Service;


use App\Entity\Figure;
use App\Entity\Picture;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * PictureService class
 */
class PictureService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;
    /**
     * @var ContainerBagInterface
     */
    private $containerBagInterface;
    /**
     * @var FileUploaderService
     */
    private $fileUploaderService;
    /**
     * @var LoggerInterface
     */
    private $loggerInterface;

    /**
     * __construct PictureService
     *
     * @param EntityManagerInterface $entityManagerInterface
     * @param ContainerBagInterface $containerBagInterface
     * @param FileUploaderService $fileUploaderService
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(EntityManagerInterface $entityManagerInterface, ContainerBagInterface $containerBagInterface, FileUploaderService $fileUploaderService, LoggerInterface $loggerInterface){

        $this->entityManagerInterface = $entityManagerInterface;
        $this->containerBagInterface = $containerBagInterface;
        $this->fileUploaderService = $fileUploaderService;
        $this->loggerInterface = $loggerInterface;
    }

    /**
     * addPicture
     *
     * @param File $file
     * @param Figure $figure
     * @return mixed
     */
    public function addPicture(File $file, Figure $figure)
    {
        try
        {
            $picture = new Picture();
            $picture->setPath($this->fileUploaderService->upload($file, 'tricks'));
            $picture->setDefaultPicture(false);
            $picture->setFigure($figure);
            $this->entityManagerInterface->persist($picture);
            $this->entityManagerInterface->flush();
        }
        catch(\Exception $e)
        {
            $this->loggerInterface->debug('pb de création de picture : '.$e->getMessage());
            return $e->getMessage();
        }

        return $picture;
    }

    /**
     * deletePicture
     *
     * @param [type] $picture
     * @return boolean
     */
    public function deletePicture($picture): bool
    {
        if($picture->getDefaultPicture() === true)
        {
            return false;
        }
        else
        {
            unlink($this->containerBagInterface->get('files_directory').'/tricks/'.$picture->getPath());

            $this->entityManagerInterface->remove($picture);
            $this->entityManagerInterface->flush();

            return true;
        }
    }

    /**
     * defaultPicture
     *
     * @param Picture $picture
     * @return boolean
     */
    public function defaultPicture(Picture $picture): bool
    {
        try
        {
            $this->razDefaultPicture($picture->getFigure());
            $picture->setDefaultPicture(true);
            $this->entityManagerInterface->persist($picture);
            $this->entityManagerInterface->flush();
        }
        catch(\Exception $e)
        {
            return false;
        }

        return true;
    }

    /**
     * razDefaultPicture
     *
     * @param Figure $figure
     * @return void
     */
    private function razDefaultPicture(Figure $figure): void
    {
        $pictures = $figure->getPictures();
        foreach($pictures as $picture){
            $picture->setDefaultPicture(false);
            $this->entityManagerInterface->persist($picture);
        }
        $this->entityManagerInterface->flush();
    }

}