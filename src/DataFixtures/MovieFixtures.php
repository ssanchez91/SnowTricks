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
            ['name'=>'melon', 'url'=>'https://youtu.be/OMxJRz06Ujc'],
            ['name'=>'method', 'url'=>'https://youtu.be/_hxLS2ErMiY'],
            ['name'=>'mute', 'url'=>'https://youtu.be/CflYbNXZU3Q'],
            ['name'=>'nose', 'url'=>'https://youtu.be/Px2YuKQVS_g'],
            ['name'=>'seatbelt', 'url'=>'https://youtu.be/4vGEOYNGi_c'],
            ['name'=>'tail', 'url'=>'https://youtu.be/Kv0Ah4Xd8d0'],
            ['name'=>'taipan', 'url'=>'https://youtu.be/J8s5iKqgGmA'],
            ['name'=>'stalefish', 'url'=>'https://youtu.be/f9FjhCt_w2U'],
            ['name'=>'tindy', 'url'=>'https://youtu.be/v8_mELZvdg4']
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
