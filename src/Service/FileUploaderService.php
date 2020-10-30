<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 21/10/2020
 * Time: 09:49
 */

namespace App\Service;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    private $targetDirectory;
    private $slugger;
    /**
     * @var LoggerInterface
     */
    private $loggerInterface;

    public function __construct($targetDirectory, SluggerInterface $slugger, LoggerInterface $loggerInterface)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->loggerInterface = $loggerInterface;
    }

    public function upload(UploadedFile $file, string $detailTargetDirectory)
    {

        $this->loggerInterface->debug('le chemin complet est : '.$detailTargetDirectory);
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory().'/'.$detailTargetDirectory, $fileName);
        } catch (FileException $e) {

            // ... handle exception if something happens during file upload
            $this->loggerInterface->debug('Problème lors de la copie du fichier : '.$e->getMessage());
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}