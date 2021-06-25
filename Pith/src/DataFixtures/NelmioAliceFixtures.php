<?php

namespace App\DataFixtures;

use App\DataFixtures\Faker\Provider\PetProvider;
use App\DataFixtures\Faker\Provider\UserProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Nelmio\Alice\Loader\NativeLoader;

class NelmioAliceFixtures extends Fixture
{
    public function load(ObjectManager $em)
    {
        $faker = Factory::create('fr_FR');

        $faker->addProvider(new UserProvider($faker)); 
        $faker->addProvider(new PetProvider($faker));

        $loader = new NativeLoader($faker);

        $entities = $loader->loadFile(__DIR__ . '/fixtures.yaml')->getObjects();

        foreach ($entities as $entity) {
            $em->persist($entity);
        };

        $em->flush();
    }
}
