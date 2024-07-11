<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\MicroPostRepository;
use App\Repository\ProfileRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{

    private array $messages = [
        "Hello", "Hi", "Bye"
    ];

    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager, ProfileRepository $profiles, MicroPostRepository $posts): Response
    {

        /* $user = new User();
        $user->setEmail("kiskacsa@example.hu");
        $user->setPassword('12345678');

        $profile = new Profile();
        $profile->setUser($user);
        
        $entityManager->persist($profile);
        $entityManager->flush(); */

        /* $profile = $profiles->find(1);

        $entityManager->remove($profile);
        $entityManager->flush(); */

        // $post = new MicroPost();
        // $post->setTitle('Hello');
        // $post->setText('Hello');
        // $post->setCreatedAt(new DateTime());

        $post = $posts->find(10);
        
        // $comment = new Comment();
        // $comment->setText('Hello');
        // $comment->setPost($post);
        // $post->addComment($comment);

        // $entityManager->persist($post);
        // $entityManager->persist($comment);
        // $entityManager->flush();


        // $comment = $post->getComments()[0];
        // $post->removeComment($comment);
        // $entityManager->persist($post);
        // $entityManager->flush();


        //dd($post);


        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' =>3,
            ]
        );
    }

    #[Route("/messages/{id<\d+>}",name:"app_show_one")]
    public function showOne(int $id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
    }
}
