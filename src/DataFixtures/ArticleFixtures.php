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

class ArticleFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        //Catégories
        $arrayCategories = array(
            'Economie', 
            'Sciences',
            'Tech',
            'Monde',
            'Sport',
            'Politique',
            'Santé'
        );
        $randCategorie = [];
        foreach($arrayCategories as $cat) {
            $categorie = new Categorie();
            if($faker->hexcolor != '#ffffff' && $faker->hexcolor != '#000000') {
                $categorie->setCouleur($faker->hexcolor);
            }
            $manager->persist($categorie);
            $randCategorie[] = $categorie;
        }
        $manager->flush();

        // Articles
        for($i = 0; $i <= 100; $i++) {
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
        }
        $manager->flush();
    }
}
