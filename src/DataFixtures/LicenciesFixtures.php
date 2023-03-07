<?php

namespace App\DataFixtures;

use App\Entity\Licencie;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LicenciesFixtures extends Fixture implements DependentFixtureInterface
{
    private $images = ['avatar-1.jpg', 'avatar-2.jpg', 'avatar-3.jpg', 'avatar-4.jpg', 'avatar-5.jpg', 'avatar-6.jpg', 'avatar-7.jpg', 'avatar-8.jpg', 'avatar-9.jpg',
                        'avatar-10.jpg', 'avatar-11.jpg', 'avatar-12.jpg', 'avatar-13.jpg', 'avatar-14.jpg', 'avatar-15.jpg', 'avatar-16.jpg', 'avatar-17.jpg', 'avatar-18.jpg',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($lcl = 1; $lcl <= 78; $lcl++){
            $this->createLicencie(
                $faker->lastName(),
                $faker->firstName(),
                $faker->dateTimeBetween('-5 years', '+2 month'),
                $manager
            );
        }

        $manager->flush();
    }

    public function createLicencie(
        string $nom,
        string $prenom,
        DateTimeInterface $date,
        ObjectManager $manager
    ){
        $licencie = new Licencie();
        $licencie->setNom($nom);
        $licencie->setPrenom($prenom);
        $licencie->setImageUrl($this->images[array_rand($this->images, 1)]);
        $licencie->setDateEntree($date);

        // Ajoute le licencié dans une équipe
        for($count = 0; $count <= random_int(1, 5); $count++)
        {
            $equipe = $this->getReference('equipe-'.rand(1,15));
            $licencie->addEquipe($equipe);
        }

        $manager->persist($licencie);
        return $licencie;
    }

    public function getDependencies(): array
    {
        return [
            EquipesFixtures::class,
        ];
    }
}
