<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;

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

        for($i = 0; $i <= 10; $i++) {
            $article = new Article();
            $article->setTitre($faker->sentence($nbWords = 6, $variableNbWords = true))
                ->setContenu($faker->text($maxNbChars = 200))
                ->setCreation(new \Datetime('now'))
                ->setModification(new \Datetime('now'))
                ->setEcrivain($writer)
                ->setImage(null)
            ;
            $manager->persist($article);
        }

        $manager->flush();
    }
}
