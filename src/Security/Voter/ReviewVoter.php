<?php

namespace App\Security\Voter;

use App\Entity\Review;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ReviewVoter extends Voter
{
    private $security;

    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['REVIEW_EDIT']) && $subject instanceof \App\Entity\Review;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'REVIEW_EDIT':
                if ($subject->getAutor == $user->getUsername()) { return true; }

                break;
        }

        return false;
    }
}
