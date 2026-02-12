<?php
namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/posts')]
class PostControllerOld extends AbstractController
{
    #[Route('/', name: 'post_list')]
    public function list(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAllPosts();
        return $this->render('post/list.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }

    #[Route('/new', name: 'post_new')]
    public function new(
       Request $request,
       EntityManagerInterface $entityManager 
    ): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
        }
        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'post_update')]
    public function update(): Response
    {
        return $this->render('post/list.html.twig');
    }

    #[Route('/{id}', name: 'post_delete')]
    public function delete(): Response
    {
        return $this->render('post/list.html.twig');
    }
}