<?php
/**
 * TagCategory voter.
 */

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TagCategoryVoter.
 */
class TagCategoryVoter extends Voter
{
    public const MANAGE = 'manage';

    protected function supports(string $attribute, $subject): bool
    {
        return self::MANAGE === $attribute;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ('ROLE_ADMIN') {
            return true;
        }

        return false;
    }
}
