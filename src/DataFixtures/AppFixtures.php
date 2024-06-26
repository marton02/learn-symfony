<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $microPost1 = new MicroPost();
        $microPost1->setTitle("Welcome to Hungary!");
        $microPost1->setText("Welcome to Hungary");
        $microPost1->setCreatedAt(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle("Welcome to USA!");
        $microPost2->setText("Welcome to USA");
        $microPost2->setCreatedAt(new DateTime());
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle("Welcome to UK!");
        $microPost3->setText("Welcome to UK");
        $microPost3->setCreatedAt(new DateTime());
        $manager->persist($microPost3);

        $manager->flush();
    }
}
