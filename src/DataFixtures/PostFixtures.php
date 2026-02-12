<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $admin */
        $admin = $this->getReference('admin-user', User::class);

        // Créer des posts
        for ($i = 1; $i <= 10; $i++) {
            $categoryIndex = random_int(1, 3);
            $category = $this->getReference('category-' . $categoryIndex, Category::class);

            $post = new Post();
            $post->setTitle("Article $i");
            $post->setContent("Contenu de l'article numéro $i");
            $post->setPublishedAt(new \DateTimeImmutable());
            $post->setAuthor($admin); // relation ManyToOne
            $post->setCategory($category); // relation ManyToOne


            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}