<?php

namespace App\DataFixtures;

use App\Entity\Equipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EquipesFixtures extends Fixture implements DependentFixtureInterface
{
    private $images = ['equipe-1.png', 'equipe-2.jpeg', 'equipe-3.jpeg', 'equipe-4.png', 'equipe-5.png', 'equipe-6.png', 'equipe-7.png', 'equipe-8.png', 'equipe-9.png'];

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        for($equipe = 1; $equipe <= 15; $equipe++){
            $this->createEquipe($faker->sentence(3), $manager);
        }

        $manager->flush();
    }

    public function createEquipe(string $name, ObjectManager $manager)
    {
        $equipe = new Equipe();
        $equipe->setNom($name);
        $equipe->setImageUrl($this->images[array_rand($this->images, 1)]);
        $sport = $this->getReference('sport-'.rand(1,15));
        $equipe->setSport($sport);

        $manager->persist($equipe);

        return $equipe;
    }

    public function getDependencies(): array
    {
        return [
            SportsFixtures::class,
        ];
    }
}
