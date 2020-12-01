<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Categorie;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategorieFixtures extends Fixture implements DependentFixtureInterface
{
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
            $categorie->setLibelle($cat);
            if($faker->hexcolor != '#ffffff' && $faker->hexcolor != '#000000') {
                $categorie->setCouleur($faker->hexcolor);
            }
            $manager->persist($categorie);
            $randCategorie[] = $categorie;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
