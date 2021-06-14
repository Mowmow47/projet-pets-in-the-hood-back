<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Nelmio\Alice\Loader\NativeLoader;
class NelmioAliceFixtures extends Fixture
{
    
        public function load(ObjectManager $em)
    {
        $faker = Factory::create('fr_BE');

        $loader = new NativeLoader($faker);

        $entities = $loader->loadFile(__DIR__ . '/fixtures.yaml')->getObjects();

        foreach ($entities as $entity) {
            $em->persist($entity);
        };

        $em->flush();
    }
}
