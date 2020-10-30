<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public const MOVIE_REFERENCE = 'movie_';

    public function load(ObjectManager $manager)
    {
        $listMovie = [
            ['name'=>'indy', 'url'=>'https://www.youtube.com/embed/6yA3XqjTh_w'],
            ['name'=>'melon', 'url'=>'https://www.youtube.com/embed/51sQRIK-TEI'],
            ['name'=>'method', 'url'=>'https://www.youtube.com/embed/_hxLS2ErMiY'],
            ['name'=>'mute', 'url'=>'https://www.youtube.com/embed/M5NTCfdObfs'],
            ['name'=>'nose', 'url'=>'https://www.youtube.com/embed/51sQRIK-TEI'],
            ['name'=>'seatbelt', 'url'=>'https://www.youtube.com/embed/4vGEOYNGi_c'],
            ['name'=>'tail', 'url'=>'https://www.youtube.com/embed/_Qq-YoXwNQY'],
            ['name'=>'taipan', 'url'=>'https://www.youtube.com/embed/J8s5iKqgGmA'],
            ['name'=>'stalefish', 'url'=>'https://www.youtube.com/embed/f9FjhCt_w2U'],
            ['name'=>'tindy', 'url'=>'https://www.youtube.com/embed/v8_mELZvdg4']
        ];

        foreach($listMovie as $row)
        {
            $figure = $this->getReference(FigureFixtures::FIGURE_REFERENCE.$row['name']);

            $movie = new Movie();
            $movie->setUrl($row['url']);
            $movie->setFigure($figure);
            $this->addReference(self::MOVIE_REFERENCE.$row['name'], $movie);

            $manager->persist($movie);

        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            FigureFixtures::class,
        ];
    }
}
