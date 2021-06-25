<?php
namespace App\DataFixtures\Faker\Provider;

class PetProvider
{
    public static function PetName()
    {
        $petnames = ['Medor', 'Rex', 'Felix', 'Lady', 'Simba', 'Leo', 'Berliose', 'Omaley', 'Tina', 'Lady', 'Chanel', 'Samy', 'Gribouille', 'Okaïdo'];
        shuffle($petnames);
        return $petnames[0];
    }

    public static function PetBreed($type)
    {
        $petBreeds = [
            'chien' => ['American Bully', 'Beagle', 'Berger allemand', 'Bichon maltais', 'Bouvier bernois', 'Chihuahua', 'Dalmatien', 'Labrador'], 
            'chat' => ['Abyssin', 'Européen', 'Bengal', 'Bleu Russe', 'British Shorthair', 'Chartreux', 'Himalayen', 'Maine Coon', 'Mau égyptien']
        ];
        shuffle($petBreeds[$type]);
        return $petBreeds[$type][0];
    }
} 