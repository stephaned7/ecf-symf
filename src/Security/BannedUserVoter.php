<?php
namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;

class BannedUserVoter extends Voter
{
    protected function supports($attribute, $subject):bool
    {
        return $attribute === 'IS_NOT_BANNED';
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token):bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return true;
        }

        if ($user->isBanned()) {
            return false;
        }

        return true;
    }
}