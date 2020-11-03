<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public const PICTURE_REFERENCE = 'picture_';

    public function load(ObjectManager $manager)
    {
        $listPicture = ['indy', 'melon', 'method', 'mute', 'nose', 'seatbelt', 'stalefish', 'tail', 'taipan', 'tindy'];

        foreach($listPicture as $pictureName)
        {
            $picture = new Picture();
            $picture->setPath($pictureName.'.jpg');
            $picture->setDefaultPicture(true);
            $picture->setFigure($this->getReference(FigureFixtures::FIGURE_REFERENCE.$pictureName));
            $this->addReference(self::PICTURE_REFERENCE.$pictureName, $picture);
            $manager->persist($picture);
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
