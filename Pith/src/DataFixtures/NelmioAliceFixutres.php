<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class NelmioAliceFixutres extends Fixture
{
    public function load(ObjectManager $em)
    {
        $faker = Factory::create('fr_FR');

        $loader = new NativeLoader($faker);

        $entities = $loader->loadFile(__DIR__ . '/fixtures pet.yaml')->getObjects();

        foreach ($entities as $entity) {
            $em->persist($entity);
        };

        $em->flush();
    }
}
