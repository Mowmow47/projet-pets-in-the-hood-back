<?php

namespace App\DataFixtures;

use \Entity\Breed;
use \Entity\Type;
use \Entity\Pet;
use \Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator as DoctrinePopulator;

class FakerPopulatorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $populator = new DoctrinePopulator($faker, $manager);
        $populator->addEntity(Pet::class, 20);

        $populator->addEntity(Breed::class, 20, [
            'name' => function() use ($faker) { return $faker->name(); },
        ]);

        $populator->addEntity(Type::class, 2, [
            'name' => function() use ($faker) { return ucfirst($faker->words(2, true)); },
        ]);

        $createdEntities = $populator->execute();

        foreach($createdEntities[Pet::class] as $movie) {
            shuffle($createdEntities[Breed::class]);
            for ($i=0; $i < rand(1,5); $i++) { 
                $movie->addGenre($createdEntities[Breed::class][$i]);
            }
        }
        $manager->flush();
    }
}
