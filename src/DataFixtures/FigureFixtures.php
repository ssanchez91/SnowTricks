<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    public const FIGURE_REFERENCE = 'figure_';

    public function load(ObjectManager $manager)
    {
        $listFigure = ['indy', 'melon', 'method', 'mute', 'nose', 'seatbelt', 'stalefish', 'tail', 'taipan', 'tindy'];

        foreach($listFigure as $figureName)
        {
            $listCategoryName = ['Flip', 'Grab', 'Rotation'];
            $cat = array_rand($listCategoryName, 1);

            $user = $this->getReference(UserFixtures::USER_REFERENCE.rand(0,9));

            $figure = new Figure();
            $figure->setSlug($figureName);
            $figure->setName(ucfirst($figureName));
            $figure->setDescription("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
            $figure->setCreatedAt(new \DateTime());
            $figure->setUpdatedAt(new \DateTime());
            $figure->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE.$listCategoryName[$cat]));
            $figure->setUser($user);

            $this->addReference(self::FIGURE_REFERENCE.$figureName, $figure);

            $manager->persist($figure);
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
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
