<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //crée notre faker pour générer de belles données aléatoires en français !
        $faker = \Faker\Factory::create("fr_FR");

        //l'ordre est important : on commence par créer nos catégories
        //le nom des 5 catégories de base qui sont toujours les mêmes
        $categoryNames = ["Travel & Adventure", "Sport", "Entertainment", "Human Relations", "Others",];
        foreach($categoryNames as $name){
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }
        $manager->flush();

        //je re-récupère toutes mes catégories depuis la bdd pour avoir de belles instances toutes propres
        $categoryRepository = $manager->getRepository(Category::class);
        $allCategories = $categoryRepository->findAll();

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
            $wish->setLikes($faker->numberBetween(0, 5000));
            $wish->setCategory($faker->randomElement($allCategories));

            //demande à doctrine de sauvegarder ce wish
            $manager->persist($wish);
        }

        //exécute la requête sql
        $manager->flush();
    }
}
