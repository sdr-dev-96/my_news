<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Commentaire;
use App\Entity\User;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\DataFixtures\ArticleFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class CommentaireFixture extends Fixture implements DependentFixtureInterface
{
    private $_userRepo;
    private $_articleRepo;

    public function __construct(ArticleRepository $articleRepo, UserRepository $userRepo)
    {
        $this->_userRepo    = $articleRepo;
        $this->_articleRepo = $userRepo;
    }

    public function load(ObjectManager $manager)
    {
        for($j = 0; $j < 20; $j++) {
            // Commentaire
            $article    = $this->_articleRepo->findRandomArticle(rand(0,1));
            $user       = $this->_userRepo->findRandomUserByRole('["ROLE_USER"]');
            $comm       = new Commentaire();
            $comm->setUser($foo)
                ->setArticle($article)
                ->setNote($faker->numberBetween($min = 0, $max = 5))
                ->setTexte($faker->text($maxNbChars = 500))
                ->setCreation($faker->dateTimeBetween($startDate = $article->getCreation(), $endDate = 'now', $timezone = null))
                ->setValid(rand(0,1) == 1)
            ;
            $manager->persist($comm);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ArticleFixtures::class,
        );
    }
}
