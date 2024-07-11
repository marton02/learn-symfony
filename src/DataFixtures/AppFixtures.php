<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('bela@test.net');
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1,
                '1234567'
            )
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('test@test.net');
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2,
                '1234567'
            )
        );
        $manager->persist($user2);

        $microPost1 = new MicroPost();
        $microPost1->setTitle("Welcome to Hungary!");
        $microPost1->setText("Welcome to Hungary");
        $microPost1->setAuthor($user1);
        $microPost1->setCreatedAt(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle("Welcome to USA!");
        $microPost2->setText("Welcome to USA");
        $microPost2->setAuthor($user1);
        $microPost2->setCreatedAt(new DateTime());
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle("Welcome to UK!");
        $microPost3->setText("Welcome to UK");
        $microPost3->setAuthor($user2);
        $microPost3->setCreatedAt(new DateTime());
        $manager->persist($microPost3);

        $manager->flush();
    }
}
