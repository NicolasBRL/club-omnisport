<?php

namespace App\DataFixtures;

use App\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SportsFixtures extends Fixture
{
    private $counter = 1;
    private $images = ['football.jpg', 'basket.jpg', 'golf.jpg', 'karate.jpg', 'rugby.jpg', 'tennis.jpg', 'volley.jpg'];

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        for($sport = 1; $sport <= 15; $sport++){
            $this->createSport($faker->text(15), $manager);
        }

        $manager->flush();
    }

    public function createSport(string $name, ObjectManager $manager)
    {
        $sport = new Sport();
        $sport->setNom($name);
        $sport->setImageUrl($this->images[array_rand($this->images, 1)]);
        $manager->persist($sport);

        $this->addReference('sport-'.$this->counter, $sport);
        $this->counter++;

        return $sport;
    }
}
