<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Tech',
        'Sport',
        'Lifestyle',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $index => $name) {
            $category = new Category();
            $category->setName($name);
            $category->setContent('Contenu de la catégorie ' . $name);

            $manager->persist($category);

            // Référence utilisable dans PostFixtures
            $this->addReference('category-' . ($index + 1), $category);
        }

        $manager->flush();
    }
}