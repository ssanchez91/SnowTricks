<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 29/10/2020
 * Time: 15:08
 */

namespace App\Service;


use App\Entity\Figure;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class MovieService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;
    /**
     * @var LoggerInterface
     */
    private $loggerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface, LoggerInterface $loggerInterface){

        $this->entityManagerInterface = $entityManagerInterface;
        $this->loggerInterface = $loggerInterface;
    }

    public function deleteMovie(Movie $movie): bool
    {
        try
        {
            $this->entityManagerInterface->remove($movie);
            $this->entityManagerInterface->flush();

            return true;
        }
        catch(\PDOException $e)
        {
             return false;
        }
    }

    public function checkUrl(string $url): bool
    {
        $patern = '^https:\/\/www.youtube.com\/embed\/[a-zA-Z0-9-_]+^';

        if(preg_match($patern, $url))
        {
            return true;
        }

        return false;
    }

    public function addMovie(string $url, Figure $figure)
    {
        try
        {
            $movie = new Movie();
            $movie->setUrl($url);
            $movie->setFigure($figure);

            $this->entityManagerInterface->persist($movie);
            $this->entityManagerInterface->flush();
        }
        catch(\PDOException $e)
        {
            return false;
        }

        return $movie;
    }
}