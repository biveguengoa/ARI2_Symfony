<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture {

    protected Generator $faker;

    public function __construct()  {
        $this->faker = Factory::create('fr_FR');
    }
    
    public function load(ObjectManager $manager): void {
        $categories = ["Science-Fiction", "Mystère/Thriller", "Romance", "Fantasy", "Poésie",
        "Non-Fiction", "Aventure", "Manga", "BD", "Autres"];

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($this->faker->userName);
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword('password' .$i);

            $users[] = $user;
            $manager->persist($user);
        }

        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle("Livre " . $i);
            $book->setDescription($this->faker->realTextBetween(100, 300));
            $book_cat = [];
            for($j = 0; $j < (mt_rand(1, 3)); $j++) {
                $book_cat[] = $categories[mt_rand(0, count($categories) -1)] ;
            }
            $book->setCategories($book_cat);
            $book->setTheUser($users[mt_rand(0, count($users) -1)]);

            $manager->persist($book);
        }

    

        $manager->flush();
    }
}
