<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //crée notre faker pour générer de belles données aléatoires en français !
        $faker = \Faker\Factory::create("fr_FR");

        //exécute le code 1000 fois !
        for($i = 0; $i < 1000; $i++) {
            //crée un wish vide
            $wish = new Wish();

            //hydrate le wish
            $wish->setTitle($faker->sentence());
            $wish->setDescription($faker->realText());
            $wish->setAuthor($faker->userName);
            $wish->setIsPublished($faker->boolean(90));
            $wish->setDateCreated($faker->dateTimeBetween('- 2 years'));

            //demande à doctrine de sauvegarder ce wish
            $manager->persist($wish);
        }

        //exécute la requête sql
        $manager->flush();
    }
}
