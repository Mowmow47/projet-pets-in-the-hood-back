<?php
namespace App\DataFixtures\Faker\Provider;

class UserProvider
{
    public static function UserFirstName()
    {
        $firstnames = ['Martin', 'Jean-Luc', 'Frank', 'Denis', 'Agnès', 'George'];
        shuffle($firstnames);
        return $firstnames[0];
    }

    public static function UserLastName()
    {
        $lastname = ['Varda', 'Coppola', 'Jenkins', 'Wachowski', "Wang"];
        shuffle($lastname);
        return $lastname[0];
    }
} 