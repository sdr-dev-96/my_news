<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Admin
        $admin   = new User();
        $admin->setEmail('admin@news.com')
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin1234'))
            ->setNom($faker->lastName)
            ->setPrenom($faker->firstNameMale)
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($admin);

        // User
        $user   = new User();
        $user->setEmail('user@news.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'user1234'))
            ->setNom($faker->lastName)
            ->setPrenom($faker->firstNameFemale)
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user);

        // Ecrivain
        $writer   = new User();
        $writer->setEmail('writer@news.com')
            ->setPassword($this->passwordEncoder->encodePassword($writer, 'writer1234'))
            ->setNom($faker->lastName)
            ->setPrenom($faker->firstNameMale)
            ->setRoles(['ROLE_WRITER'])
        ;
        $manager->persist($writer);

        $manager->flush();

        //Catégories
        $arrayCategories = array(
            array('libelle' => 'Economie','couleur' => 'success'),
            array('libelle' => 'Sciences - Technologie','couleur' => 'primary'),
            array('libelle' => 'Monde','couleur' => 'warning'),
            array('libelle' => 'Sport','couleur' => 'danger'),
            array('libelle' => 'Politique','couleur' => 'info'),
            array('libelle' => 'Santé','couleur' => 'secondary')
        );

        for($i = 0; $i <= 5; $i++) {
            $categorie = new Categorie();
            $categorie
                ->setLibelle($arrayCategories[$i]['libelle'])
                ->setCouleur($arrayCategories[$i]['couleur'])
            ;
            $manager->persist($categorie);
            // Article
            for($j = 0; $j <= 10; $j++) {
                $article = new Article();
                $article->setTitre($faker->sentence($nbWords = 6, $variableNbWords = true))
                    ->setContenu($faker->text($maxNbChars = 2000))
                    ->setCreation($faker->dateTimeBetween($startDate = '-10 years', $endDate = '-1 month', $timezone = null))
                    ->setModification(null)
                    ->setEcrivain($writer)
                    ->setImage(null)
                    ->setCategorie($categorie)
                ;
                $manager->persist($article);

                // Foo Users
                for($k = 0; $k <= 10; $k++) {
                    $foo   = new User();
                    $foo->setEmail($faker->email)
                        ->setPassword($this->passwordEncoder->encodePassword($foo, $faker->password))
                        ->setNom($faker->lastName)
                        ->setPrenom($faker->firstNameMale)
                        ->setRoles(['ROLE_USER'])
                    ;
                    $manager->persist($foo);

                    // Commentaire
                    $comm = new Commentaire();
                    $comm->setUser($foo)
                        ->setArticle($article)
                        ->setNote($faker->numberBetween($min = 0, $max = 5))
                        ->setTexte($faker->text($maxNbChars = 500))
                        ->setCreation($faker->dateTimeBetween($startDate = $article->getCreation(), $endDate = 'now', $timezone = null))
                        ->setValid(rand(0,1) == 1)
                    ;
                    $manager->persist($comm);
                }
            }
        }
        $manager->flush();
    }
}