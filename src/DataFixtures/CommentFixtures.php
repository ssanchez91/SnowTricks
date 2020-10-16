<?php

namespace App\DataFixtures;

use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $listFigure = ['indy', 'melon', 'method', 'mute', 'nose', 'seatbelt', 'stalefish', 'tail', 'taipan', 'tindy'];

        foreach($listFigure as $figureName)
        {
            $comment = new Comment();
            $comment->setContent("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
            $comment->setCreatedAt(new \DateTime());
            $comment->setFigure($this->getReference(FigureFixtures::FIGURE_REFERENCE.$figureName));
            $comment->setUser($this->getReference(UserFixtures::USER_REFERENCE.rand(0,9)));

            $manager->persist($comment);
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
            UserFixtures::class,
        ];
    }
}
