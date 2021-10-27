<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\DataFixtures\CategorieFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    private $_categorieRepo;
    private $_userRepo;

    public function __construct(CategorieRepository $categorieRepo, UserRepository $userRepo)
    {
        $this->_categorieRepo   = $categorieRepo;
        $this->_userRepo        = $userRepo;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Articles
        for($i = 0; $i <= 100; $i++) {
            $article    = new Article();
            $categorie  = $this->_categorieRepo->findRandomCategorie();
            $writer     = $this->_userRepo->findRandomUserByRole('["ROLE_AUTHOR"]');
            $article->setTitre(str_replace('.', '', $faker->sentence($nbWords = 6, $variableNbWords = true)))
                ->setContenu($faker->text($maxNbChars = 2000))
                ->setCreation($faker->dateTimeBetween($startDate = '-10 years', $endDate = '-1 month', $timezone = null))
                ->setModification(null)
                ->setEcrivain($writer)
                ->setImage(null)
                ->setCategorie($categorie)
                ->setOnline(rand(0,1) == 1)
                ->setUrl('test')
            ;
            $manager->persist($article);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategorieFixtures::class,
        );
    }
}
