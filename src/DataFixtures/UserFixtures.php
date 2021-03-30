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

class UserFixtures extends Fixture
{

    private $_passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->_passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Admin
        $admin   = new User();
        $admin->setEmail('admin@news.com')
            ->setPassword($this->_passwordEncoder->encodePassword($admin, 'admin1234'))
            ->setNom('Rodrigues')
            ->setPrenom('Stefane')
            ->setRoles(['ROLE_ADMIN'])
            ->setCreation($faker->dateTime($max = '-6 years', $timezone = null))
        ;
        $manager->persist($admin);

        // User
        $user   = new User();
        $user->setEmail('user@news.com')
            ->setPassword($this->_passwordEncoder->encodePassword($user, 'user1234'))
            ->setNom('Rodrigues')
            ->setPrenom('Stefane')
            ->setRoles(['ROLE_USER'])
            ->setCreation($faker->dateTime($max = '-6 years', $timezone = null))
        ;
        $manager->persist($user);

        // Ecrivain
        $writer   = new User();
        $writer->setEmail('writer@news.com')
            ->setPassword($this->_passwordEncoder->encodePassword($writer, 'writer1234'))
            ->setNom($faker->lastName)
            ->setPrenom($faker->firstNameMale)
            ->setRoles(['ROLE_AUTHOR'])
            ->setCreation($faker->dateTime($max = '-6 years', $timezone = null))
        ;
        $manager->persist($writer);

        // Ecrivains
        for($i = 0; $i < 3; $i++) {
            $writer   = new User();
            $writer->setEmail($faker->email)
                ->setPassword($this->_passwordEncoder->encodePassword($writer, $faker->password))
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setRoles(['ROLE_AUTHOR'])
                ->setCreation($faker->dateTime($max = '-5 years', $timezone = null))
            ;
            $manager->persist($writer);
        }
        $manager->flush();

        // Foo Users
        for($j = 0; $j < 50; $j++) {
            $foo   = new User();
            $foo->setEmail($faker->email)
                ->setPassword($this->_passwordEncoder->encodePassword($foo, $faker->password))
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstNameMale)
                ->setRoles(['ROLE_USER'])
                ->setCreation($faker->dateTime($max = '-5 years', $timezone = null))
            ;
            $manager->persist($foo);
        }
        $manager->flush();
    }
}