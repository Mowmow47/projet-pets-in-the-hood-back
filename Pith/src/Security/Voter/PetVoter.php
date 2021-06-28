<?php

namespace App\Security\Voter;

use App\Entity\Pet;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PetVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['PET_ADD', 'PET_EDIT', 'PET_DELETE'])
            && $subject instanceof Pet;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
       
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'PET_ADD':
                return true;
                break;
            case 'PET_EDIT':
                if ($user->getId() == $subject->getUser()->getId() ||
                    in_array('ROLE_ADMIN', $user->getRoles())
                ) {
                    return true;
                }
                break;
            case 'PET_DELETE':
                if ($user->getId() == $subject->getUser()->getId() ||
                in_array('ROLE_ADMIN', $user->getRoles())
                ) {
                return true;
                }
                
        }

        return false;
    }
}
