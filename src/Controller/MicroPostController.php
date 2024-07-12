<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(EntityManagerInterface $entityManager, MicroPostRepository $microPosts): Response
    {
        // $microPost = new MicroPost();
        // $microPost->setTitle("It comes from controller");
        // $microPost->setText("Hi!");
        // $microPost->setCreatedAt(new DateTime());
        // $entityManager->persist($microPost);
        // $entityManager->flush();

        // $microPost = $microPosts->find(1);
        // $microPost->setText("Kiskacsa");
        // $entityManager->flush();
        
        //dd($microPosts->findAll());
        return $this->render('micro_post/index.html.twig', [
            'posts' => $microPosts->findAllWithComments(),
        ]);
    }

    #[Route('micro-post/{id<\d+>}', name: 'app_micro_post_show')]
    #[IsGranted(MicroPost::VIEW,'microPost')]
    public function showOne(MicroPost $microPost): Response{
        return $this->render('micro_post/show.html.twig', [
            'post' => $microPost,
        ]);
    }

    #[Route('micro-post/add',name:'app_micro_post_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response{
        
        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();

            //Add a flash
            $this->addFlash("success","Your micro post have been added");

            //Redirect
            return $this->redirectToRoute("app_micro_post");
        }

        return $this->render('micro_post/add.html.twig',[
            "form" => $form,
        ]);
    }

    #[Route('micro-post/{id<\d+>}/edit',name:'app_micro_post_edit')]
    #[IsGranted(MicroPost::EDIT,'microPost')]
    public function edit(EntityManagerInterface $entityManager, Request $request, MicroPost $microPost): Response{
        
        $form = $this->createForm(MicroPostType::class, $microPost);

        $form->handleRequest($request);

        $this->denyAccessUnlessGranted(MicroPost::EDIT,'microPost');

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();

            //Add a flash
            $this->addFlash("success","Your micro post have been updated");

            //Redirect
            return $this->redirectToRoute("app_micro_post");
        }

        return $this->render('micro_post/edit.html.twig',[
            "form" => $form,
            "post" => $microPost,
        ]);
    }

    #[Route('micro-post/{id<\d+>}/comment',name:'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(EntityManagerInterface $entityManager, Request $request, MicroPost $microPost): Response{
        
        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($microPost);
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();

            //Add a flash
            $this->addFlash("success","Your comment have been added");

            //Redirect
            return $this->redirectToRoute(
                "app_micro_post_show",
                [
                    "id" => $microPost->getId(),
                ]
            );
        }

        return $this->render('micro_post/comment.html.twig',[
            "form" => $form,
            "post" => $microPost,
        ]);
    }
}
