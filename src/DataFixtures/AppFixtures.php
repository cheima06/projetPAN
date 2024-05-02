<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_FR");

        for($i = 0; $i < 5; $i++) {
            $article = new Article;
            $article->setDescription($faker->text);
            $article->setPhoto($faker->imageUrl);
            $article->setDate($faker->dateTime);
            $article->setTitre($faker->title);
            $manager->persist($article);

             // créer des catégories
             $category = new Category;
             $category->setDescription($faker->text);
             $category->setTitre($faker->title);
             $manager->persist($category);

                // créer des messages contacts

            $contact = new Contact;
            $contact->setDate($faker->dateTime);
            $contact->setEmail($faker->email);
            $contact->setFirstName($faker->firstName);
            $contact->setName($faker->lastName);
            $contact->setMessage($faker->text);
            $contact->setObject($faker->title);
            $contact->setPhoneNumber("0" . $i . "01" . $i . "10". $i . "01");
            $manager->persist($contact);

             //creer des evenements
            $event= new Event;
            $event->setName($faker->name);
            $event->setPrice($faker->randomNumber(2));
            $event->setPicture($faker->imageUrl);
            $event->setStartAt($faker->dateTime);
            $event->setEndAt($faker->dateTime);
            $event->setDescription($faker->text);
            //$event->setCategory($faker->title);
            $manager->persist($event);

            // créer des users
                $user = new User;
                $user->setAdresse($faker->address);
                $user->setEmail($faker->email);
                //$user->setRoles($faker->)
                $user->setFirstName($faker->firstName);
                $user->setName($faker->name);
                $user->setPassword($faker->password);
                $user->setSexe($faker->randomElement(['masculin', 'féminin']));
                $user->setIsVerified($faker->boolean);
                $user->setPostalCode($faker->postcode);
                $manager->persist($user);
            

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
}