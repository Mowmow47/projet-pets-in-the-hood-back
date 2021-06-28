<?php

namespace App\Security\Voter;

use \App\Entity\Advert;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AdvertVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['ADVERT_ADD', 'ADVERT_EDIT', 'ADVERT_DEACTIVATE'])
            && $subject instanceof Advert;
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
            case 'ADVERT_ADD':
                return true;
            case 'ADVERT_EDIT':
                if ($user->getId() == $subject->getUser()->getId() ||
                    in_array('ROLE_ADMIN', $user->getRoles())
                ) {
                    return true;
                }
                break;
            case 'ADVERT_DEACTIVATE':
                if ($user->getId() == $subject->getUser()->getId() ||
                in_array('ROLE_ADMIN', $user->getRoles())
                ) {
                return true;
                }
        }

        return false;
    }
}
