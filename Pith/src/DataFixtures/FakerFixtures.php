<?php

namespace App\DataFixtures;

use App\Entity\Breed;
use App\Entity\Pet;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FakerFixtures extends Fixture
{
    public function load(ObjectManager $em)
    {
        // We initialize Faker and ask for it to be in French
        $faker = Factory::create('fr_FR');

        $breeds =[];
        for ($i = 0; $i < 20; $i++) { 
            $breed = new Breed();
            $breed->setName(ucfirst($faker->words(2, true)));
            
            $em->persist($breed);
            $breeds[] = $breed;
        }

        $types = [];
        for($i = 0; $i < 2; $i++) {
            $type = new Type();
            $type->setName($faker->word());

            $em->persist($type);

            $types[] = $type;
        }

        for($i = 0; $i < 20; $i++) {
            $pet = new Pet();
            $pet->setName($faker->word());
            $pet->setdescription($faker->sentence());

            // We associate a breed and a type at random
            // We mix breeds and types
            shuffle($breeds);
            shuffle($types);

            // We associate the first of the mixed list with $pet
            $pet->setBreed($breeds[0]);
            $pet->setType($types[0]);

            $em->persist($pet);
        }


        $em->flush();
    }
}
