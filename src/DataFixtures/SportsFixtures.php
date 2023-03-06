<?php

namespace App\DataFixtures;

use App\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SportsFixtures extends Fixture
{
    private $counter = 1;
    private $sports = [
        'Football' => 'football.jpg',
        'Basket' => 'basket.jpg',
        'Golf' => 'golf.jpg',
        'Karaté' => 'karate.jpg',
        'Rugby' => 'rugby.jpg',
        'Tennis' => 'tennis.jpg',
        'Volley' => 'volley.jpg',
        'Athlétisme' => 'athletisme.jpg',
        'Cyclisme' => 'cyclisme.jpg',
        'Hockey sur glace' => 'hockey.jpg',
    ];

    public function load(ObjectManager $manager): void
    {   
        foreach($this->sports as $name => $img){
            $this->createSport($name, $img, $manager);
        }

        $manager->flush();
    }

    public function createSport(string $name, string $image, ObjectManager $manager)
    {
        $sport = new Sport();
        $sport->setNom($name);
        $sport->setImageUrl($image);
        $manager->persist($sport);
        
        $this->addReference('sport-'.$this->counter, $sport);
        $this->counter++;

        return $sport;
    }
}
