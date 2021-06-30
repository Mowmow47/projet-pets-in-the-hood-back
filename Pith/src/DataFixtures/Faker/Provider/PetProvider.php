<?php
namespace App\DataFixtures\Faker\Provider;

class PetProvider
{
    public static function PetName()
    {
        $petnames = [
            'Medor', 'Rex', 'Felix', 'Lady', 'Simba', 'Leo', 'Berliose', 'Omaley', 'Tina', 'Lady',
            'Chanel', 'Samy', 'Gribouille', 'Okaïdo', 'Mina', 'Minouche', 'Lucie', 'Naïa', 'Scoubidou', 'Mayze',
            'Spot', 'Picasso', 'Jenna', 'Noisette', 'Pirou', 'Nougat', 'Suzy', 'Boule', 'Lola', 'Chaussette',
            'Chipie', 'Diesel', 'Dipsy', 'Falco', 'Fleur', 'Maya', 'Léo', 'Noa', 'Otis', 'Plume'
        ];
        shuffle($petnames);
        return $petnames[0];
    }

    public static function PetBreed($type)
    {
        $petBreeds = [
            'chien' => [
                'American Bully', 'Beagle', 'Berger allemand', 'Bichon maltais', 'Bouvier bernois', 'Chihuahua', 'Dalmatien', 'Labrador', 'Chow-Chow', 'Jack Russell Terrier',
                'Fox Terrier', 'Setter anglais', 'Shiba Inu', 'Shih Tzu', 'Rottweiler', 'Carlin', 'Caniche', 'Boxer' ,'Épagneul breton' ,'Pinscher nain'
            ], 
            'chat' => [
                'Abyssin', 'Européen', 'Bengal', 'Bleu Russe', 'British Shorthair', 'Chartreux', 'Himalayen', 'Maine Coon', 'Mau égyptien', 'American shorthair',
                'Norvégien', 'Persan', 'Sacré de Birmanie', 'Savannah', 'Siamois', 'Sphynx', 'Burmilla', 'Angora turc', 'Devon Rex', 'Ceylan'
            ]
        ];
        shuffle($petBreeds[$type]);
        return $petBreeds[$type][0];
    }
} 