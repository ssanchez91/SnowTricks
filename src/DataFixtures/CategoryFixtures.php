<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'category_';

    public function load(ObjectManager $manager)
    {
        $listCategoryName = ['Flip', 'Grab', 'Rotation'];

        foreach($listCategoryName as $categoryName)
        {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);

            $this->addReference(self::CATEGORY_REFERENCE.$category->getName(), $category);

        }
        $manager->flush();
    }
}
