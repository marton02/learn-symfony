<?php

namespace App\Controller;

use App\Entity\MicroPost;
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
            'posts' => $microPosts->findAll(),
        ]);
    }

    #[Route('micro-post/{id<\d>}', name: 'app_micro_post_show')]
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
            $post->setCreatedAt(new DateTime());
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

    #[Route('micro-post/{id<\d>}/edit',name:'app_micro_post_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, MicroPost $microPost): Response{
        
        $form = $this->createForm(MicroPostType::class, $microPost);

        $form->handleRequest($request);

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
        ]);
    }
}
